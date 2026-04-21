<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    // LIST
    public function index(Request $request)
    {
        $query = Subject::query();

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $subjects = $query->latest()->paginate(10);

        return view('admin.subjects.index', compact('subjects'));
    }

    // CREATE
    public function create()
    {
        return view('admin.subjects.create');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:subjects,name',
        ]);

        Subject::create([
            'name' => $request->name
        ]);

        return redirect('/subjects')->with('success', 'Subject created');
    }

    // EDIT
    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        return view('admin.subjects.edit', compact('subject'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:subjects,name,' . $id,
        ]);

        $subject->update([
            'name' => $request->name
        ]);

        return redirect('/subjects')->with('success', 'Updated');
    }

    // DELETE
    public function destroy($id)
    {
        Subject::findOrFail($id)->delete();

        return back()->with('success', 'Deleted');
    }
	public function bulkDelete(Request $request)
	{
		Subject::whereIn('id', $request->ids)->delete();

		return back()->with('success', 'Selected subjects deleted');
	}
}