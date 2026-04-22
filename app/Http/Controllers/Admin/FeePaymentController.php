<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeePayment;
use App\Models\StudentFee;
use App\Models\FeePaymentItem;

class FeePaymentController extends Controller
{
    /**
     * Payment Listing + Search + Pagination
     */
	public function index(Request $request)
	{
		$query = FeePayment::with([
			'studentFee.student.user',
			'studentFee.student.class',
			'studentFee.feeType'
		]);

		/*
		|--------------------------------------------------------------------------
		| Search by Receipt No or Student Name
		|--------------------------------------------------------------------------
		*/
		if ($request->search) {
			$search = $request->search;

			$query->where(function ($q) use ($search) {
				$q->where('receipt_no', 'like', "%{$search}%")
				  ->orWhereHas('studentFee.student.user', function ($subQuery) use ($search) {
					  $subQuery->where('name', 'like', "%{$search}%");
				  });
			});
		}

		/*
		|--------------------------------------------------------------------------
		| Filter by Class
		|--------------------------------------------------------------------------
		*/
		if ($request->class_id) {
			$query->whereHas('studentFee.student', function ($q) use ($request) {
				$q->where('class_id', $request->class_id);
			});
		}

		/*
		|--------------------------------------------------------------------------
		| Filter by Payment Mode
		|--------------------------------------------------------------------------
		*/
		if ($request->payment_mode) {
			$query->where('payment_mode', $request->payment_mode);
		}

		/*
		|--------------------------------------------------------------------------
		| Filter by Status
		|--------------------------------------------------------------------------
		*/
		if ($request->status) {
			$query->where('status', $request->status);
		}

		$feePayments = $query->latest()->paginate(10);

		/*
		|--------------------------------------------------------------------------
		| Classes for Filter Dropdown
		|--------------------------------------------------------------------------
		*/
		$classes = \App\Models\SchoolClass::latest()->get();

		return view(
			'admin.fee_payments.index',
			compact('feePayments', 'classes')
		);
	}

    /**
     * Create Page
     */
	public function create()
	{
		/*
		|--------------------------------------------------------------------------
		| Load only required master data
		| Classes → initial load
		| Sections/Students/Fee Types → AJAX based
		|--------------------------------------------------------------------------
		*/

		$classes = \App\Models\SchoolClass::latest()->get();

		return view(
			'admin.fee_payments.create',
			compact('classes')
		);
	}

    /**
     * Store Payment
     */
	public function store(Request $request)
	{
		$request->validate([
			'student_id' => 'required',
			'payment_mode' => 'required',
			'payment_date' => 'required|date',
			'status' => 'required',
			'student_fee_ids' => 'required|array',
			'amount_paid' => 'required|array',
		]);

		/*
		|--------------------------------------------------------------------------
		| Generate Receipt Number
		|--------------------------------------------------------------------------
		*/
		$receiptNo = 'REC-' . date('Ymd') . '-' . rand(1000, 9999);

		/*
		|--------------------------------------------------------------------------
		| Calculate Total Paid
		|--------------------------------------------------------------------------
		*/
		$totalPaid = array_sum($request->amount_paid);

		/*
		|--------------------------------------------------------------------------
		| Create Main Payment Record
		|--------------------------------------------------------------------------
		*/
		$payment = FeePayment::create([
			'student_id' => $request->student_id,
			'receipt_no' => $receiptNo,
			'total_paid' => $totalPaid,
			'payment_mode' => $request->payment_mode,
			'transaction_id' => $request->transaction_id,
			'payment_date' => $request->payment_date,
			'remarks' => $request->remarks,
			'status' => $request->status,
		]);

		/*
		|--------------------------------------------------------------------------
		| Create Multiple Payment Items
		|--------------------------------------------------------------------------
		*/
		foreach ($request->student_fee_ids as $key => $studentFeeId) {

			if (!empty($request->amount_paid[$key])) {

				FeePaymentItem::create([
					'fee_payment_id' => $payment->id,
					'student_fee_id' => $studentFeeId,
					'amount_paid' => $request->amount_paid[$key],
				]);
			}
		}

		return redirect('/fee-payments')
			->with('success', 'Fee payment collected successfully.');
	}

    /**
     * Edit Page
     */
	public function edit($id)
	{
		$feePayment = FeePayment::with([
			'studentFee.student.user',
			'studentFee.student.class',
			'studentFee.student.section',
			'studentFee.feeType'
		])->findOrFail($id);

		$classes = \App\Models\SchoolClass::latest()->get();

		return view(
			'admin.fee_payments.edit',
			compact(
				'feePayment',
				'classes'
			)
		);
	}

    /**
     * Update Payment
     */
    public function update(Request $request, $id)
    {
        $feePayment = FeePayment::findOrFail($id);

        $request->validate([
            'student_fee_id' => 'required',
            'paid_amount' => 'required|numeric|min:1',
            'payment_mode' => 'required',
            'payment_date' => 'required|date',
            'status' => 'required',
        ]);

        $feePayment->update([
            'student_fee_id' => $request->student_fee_id,
            'paid_amount' => $request->paid_amount,
            'payment_mode' => $request->payment_mode,
            'transaction_id' => $request->transaction_id,
            'payment_date' => $request->payment_date,
            'remarks' => $request->remarks,
            'status' => $request->status,
        ]);

        return redirect('/fee-payments')
            ->with('success', 'Fee payment updated successfully.');
    }

    /**
     * Delete Single
     */
    public function destroy($id)
    {
        $feePayment = FeePayment::findOrFail($id);
        $feePayment->delete();

        return back()->with(
            'success',
            'Fee payment deleted successfully.'
        );
    }

    /**
     * Bulk Delete
     */
    public function bulkDelete(Request $request)
    {
        if (!$request->ids || count($request->ids) == 0) {
            return back()->with(
                'error',
                'Please select at least one record.'
            );
        }

        FeePayment::whereIn('id', $request->ids)->delete();

        return back()->with(
            'success',
            'Selected payments deleted successfully.'
        );
    }
	public function getStudentFees($student_id)
	{
		$studentFees = \App\Models\StudentFee::with('feeType')
			->where('student_id', $student_id)
			->get();

		return response()->json($studentFees);
	}
}