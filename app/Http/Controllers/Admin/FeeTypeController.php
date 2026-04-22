<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeeType;

class FeeTypeController extends Controller
{
    /**
     * Fee Type Listing + Search + Pagination
     */
    public function index(Request $request)
    {
        $query = FeeType::query();

        // Search by name
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $feeTypes = $query->latest()->paginate(10);

        return view('admin.fee_types.index', compact('feeTypes'));
    }

    /**
     * Create Page
     */
    public function create()
    {
        return view('admin.fee_types.create');
    }

    /**
     * Store Fee Type
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:fee_types,name',
            'amount'      => 'required|numeric|min:0',
            'frequency'   => 'required',
            'description' => 'nullable|string',
        ]);

        FeeType::create([
            'name'        => $request->name,
            'amount'      => $request->amount,
            'frequency'   => $request->frequency,
            'is_optional' => $request->has('is_optional') ? 1 : 0,
            'description' => $request->description,
        ]);

        return redirect('/fee-types')
            ->with('success', 'Fee Type created successfully.');
    }

    /**
     * Edit Page
     */
    public function edit($id)
    {
        $feeType = FeeType::findOrFail($id);

        return view('admin.fee_types.edit', compact('feeType'));
    }

    /**
     * Update Fee Type
     */
    public function update(Request $request, $id)
    {
        $feeType = FeeType::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255|unique:fee_types,name,' . $id,
            'amount'      => 'required|numeric|min:0',
            'frequency'   => 'required',
            'description' => 'nullable|string',
        ]);

        $feeType->update([
            'name'        => $request->name,
            'amount'      => $request->amount,
            'frequency'   => $request->frequency,
            'is_optional' => $request->has('is_optional') ? 1 : 0,
            'description' => $request->description,
        ]);

        return redirect('/fee-types')
            ->with('success', 'Fee Type updated successfully.');
    }

    /**
     * Delete Single
     */
    public function destroy($id)
    {
        $feeType = FeeType::findOrFail($id);
        $feeType->delete();

        return back()->with('success', 'Fee Type deleted successfully.');
    }

    /**
     * Bulk Delete
     */
    public function bulkDelete(Request $request)
    {
        if (!$request->ids || count($request->ids) == 0) {
            return back()->with('error', 'Please select at least one record.');
        }

        FeeType::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected Fee Types deleted successfully.');
    }
}