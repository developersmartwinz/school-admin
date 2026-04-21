<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassSubjectController extends Controller
{
    //
	public function index()
	{
		$classes = \App\Models\SchoolClass::with('classSubjects.subject')
			->paginate(10);

		return view('admin.class_subjects.index', compact('classes'));
	}
	public function create()
	{
		$classes = \App\Models\SchoolClass::all();
		$subjects = \App\Models\Subject::all();

		return view('admin.class_subjects.create', compact('classes','subjects'));
	}
	public function edit($class_id)
	{
		$classes = SchoolClass::all();
		$subjects = Subject::all();

		$selected = ClassSubject::where('class_id', $class_id)
			->pluck('subject_id')->toArray();

		return view('admin.class_subjects.edit', compact(
			'classes',
			'subjects',
			'class_id',
			'selected'
		));
	}
	public function destroy($class_id)
	{
		ClassSubject::where('class_id', $class_id)->delete();

		return back()->with('success', 'Deleted');
	}
}
