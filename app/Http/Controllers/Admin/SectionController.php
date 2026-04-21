<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\SchoolClass;

class SectionController extends Controller
{
	public function index()
	{
		$sections = Section::with('class')->latest()->paginate(10);
		return view('admin.sections.index', compact('sections'));
	}

	public function edit($id)
	{
		$section = Section::findOrFail($id);
		$classes = SchoolClass::all();

		return view('admin.sections.edit', compact('section', 'classes'));
	}

	public function update(Request $request, $id)
	{
		Section::findOrFail($id)->update([
			'name' => $request->name,
			'class_id' => $request->class_id
		]);

		return redirect('/sections')->with('success', 'Section updated');
	}

	public function destroy($id)
	{
		Section::findOrFail($id)->delete();
		return back()->with('success', 'Section deleted');
	}

	public function bulkDelete(Request $request)
	{
		Section::whereIn('id', $request->ids)->delete();
		return back()->with('success', 'Selected sections deleted');
	}
    //
	public function create()
	{
		$classes = SchoolClass::all();
		return view('admin.sections.create', compact('classes'));
	}

	public function store(Request $request)
	{
		Section::create([
			'name' => $request->name,
			'class_id' => $request->class_id
		]);

		return back()->with('success', 'Section created');
	}
}
