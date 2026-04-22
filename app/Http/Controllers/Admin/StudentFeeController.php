<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentFee;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\FeeType;

class StudentFeeController extends Controller
{
    /**
     * Student Fee Listing + Search + Pagination
     */
	public function index(Request $request)
	{
		$query = StudentFee::with([
			'student.user',
			'student.class',
			'student.section',
			'feeType'
		]);

		/*
		|--------------------------------------------------------------------------
		| Search by Student Name
		|--------------------------------------------------------------------------
		*/
		if ($request->search) {
			$search = $request->search;

			$query->whereHas('student.user', function ($q) use ($search) {
				$q->where('name', 'like', "%{$search}%");
			});
		}

		/*
		|--------------------------------------------------------------------------
		| Filter by Class
		|--------------------------------------------------------------------------
		*/
		if ($request->class_id) {
			$query->whereHas('student', function ($q) use ($request) {
				$q->where('class_id', $request->class_id);
			});
		}

		/*
		|--------------------------------------------------------------------------
		| Filter by Year
		|--------------------------------------------------------------------------
		*/
		if ($request->year) {
			$query->where('year', $request->year);
		}

		$students = $query->latest()->paginate(10);

		$classes = \App\Models\SchoolClass::latest()->get();

		return view(
			'admin.student_fees.index',
			compact('students', 'classes')
		);
	}

    /**
     * Create Page
     */
    public function create()
	{
		$classes = \App\Models\SchoolClass::latest()->get();
		$feeTypes = \App\Models\FeeType::latest()->get();

		return view(
			'admin.student_fees.create',
			compact('classes', 'feeTypes')
		);
	}

    /**
     * Store Student Fee
     */
	public function store(Request $request)
	{
		$request->validate([
			'student_id' => 'required',
			'year' => 'required',
			
			'fee_type_ids' => 'required|array',
			'amounts' => 'required|array',
		]);

		/*
		|--------------------------------------------------------------------------
		| Prevent Duplicate Assignment
		|--------------------------------------------------------------------------
		*/
		foreach ($request->fee_type_ids as $key => $feeTypeId) {

			$exists = StudentFee::where('student_id', $request->student_id)
				->where('fee_type_id', $feeTypeId)
				->where('year', $request->year)
				->exists();

			if (!$exists && !empty($request->amounts[$key])) {

				StudentFee::create([
					'student_id' => $request->student_id,
					'fee_type_id' => $feeTypeId,
					'amount' => $request->amounts[$key],
					'year' => $request->year,
					
				]);
			}
		}

		return redirect('/student-fees')
			->with('success', 'Student fees assigned successfully.');
	}

    /**
     * Edit Page
     */
    public function edit($id)
    {
        $studentFee = StudentFee::findOrFail($id);
       // $students = Student::with('user')->latest()->get();
        $feeTypes = FeeType::latest()->get();
		$classes = \App\Models\SchoolClass::latest()->get();

        return view(
            'admin.student_fees.edit',
            compact('studentFee', 'feeTypes','classes')
        );
    }

    /**
     * Update Student Fee
     */
    public function update(Request $request, $id)
    {
        $studentFee = StudentFee::findOrFail($id);

        $request->validate([
            'student_id'  => 'required',
            'fee_type_id' => 'required',
            'amount'      => 'required|numeric|min:0',
            'year'        => 'required',
            
        ]);

        $studentFee->update([
            'student_id'  => $request->student_id,
            'fee_type_id' => $request->fee_type_id,
            'amount'      => $request->amount,
            'year'        => $request->year,
            
        ]);

        return redirect('/student-fees')
            ->with('success', 'Student Fee updated successfully.');
    }

    /**
     * Delete Single
     */
    public function destroy($id)
    {
        $studentFee = StudentFee::findOrFail($id);
        $studentFee->delete();

        return back()->with(
            'success',
            'Student Fee deleted successfully.'
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

        StudentFee::whereIn('id', $request->ids)->delete();

        return back()->with(
            'success',
            'Selected Student Fees deleted successfully.'
        );
    }
}