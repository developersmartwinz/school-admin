<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeacherAssignment;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;


class TeacherAssignmentController extends Controller
{
    //
	public function index()
{
    $assignments = \App\Models\TeacherAssignment::with([
        'teacher.user',
        'class',
        'section',
        'subject'
    ])
    ->get()
    ->groupBy(function ($item) {
        return $item->teacher_id . '-' . $item->class_id . '-' . $item->section_id;
    });

    return view('admin.teacher_assignments.index', compact('assignments'));
}

	public function create()
	{
		$teachers = Teacher::with('user')->get();
		$classes = SchoolClass::all();
		$sections = Section::all();
		$subjects = Subject::all(); // ✅ IMPORTANT

		return view('admin.teacher_assignments.create', compact('teachers','classes','sections','subjects'));
	}

	public function store(Request $request)
	{
		$request->validate([
			'teacher_id' => 'required',
			'class_id' => 'required',
			'section_id' => 'required',
			'subject_ids' => 'required|array',
		]);

		foreach ($request->subject_ids as $subject_id) {

			$exists = \App\Models\TeacherAssignment::where([
				'teacher_id' => $request->teacher_id,
				'class_id' => $request->class_id,
				'section_id' => $request->section_id,
				'subject_id' => $subject_id,
			])->exists();

			if (!$exists) {
				\App\Models\TeacherAssignment::create([
					'teacher_id' => $request->teacher_id,
					'class_id' => $request->class_id,
					'section_id' => $request->section_id,
					'subject_id' => $subject_id,
				]);
			}
		}

		return redirect('/teacher-assignments')->with('success','Subjects assigned');
	}

	public function destroy($id)
	{
		TeacherAssignment::findOrFail($id)->delete();
		return back()->with('success','Deleted');
	}
	public function edit($teacher_id, $class_id, $section_id)
	{
		$teachers = \App\Models\Teacher::with('user')->get();
		$classes = \App\Models\SchoolClass::all();
		$sections = \App\Models\Section::all();
		$subjects = \App\Models\Subject::all();

		$selectedSubjects = \App\Models\TeacherAssignment::where([
			'teacher_id' => $teacher_id,
			'class_id' => $class_id,
			'section_id' => $section_id,
		])->pluck('subject_id')->toArray();

		return view('admin.teacher_assignments.edit', compact(
			'teachers',
			'classes',
			'sections',
			'subjects',
			'teacher_id',
			'class_id',
			'section_id',
			'selectedSubjects'
		));
	}
	public function update(Request $request)
	{
		$request->validate([
			'teacher_id' => 'required',
			'class_id' => 'required',
			'section_id' => 'required',
			'subject_ids' => 'required|array',
		]);

		// DELETE OLD
		\App\Models\TeacherAssignment::where([
			'teacher_id' => $request->teacher_id,
			'class_id' => $request->class_id,
			'section_id' => $request->section_id,
		])->delete();

		// INSERT NEW
		foreach ($request->subject_ids as $subject_id) {
			\App\Models\TeacherAssignment::create([
				'teacher_id' => $request->teacher_id,
				'class_id' => $request->class_id,
				'section_id' => $request->section_id,
				'subject_id' => $subject_id,
			]);
		}

		return redirect('/teacher-assignments')->with('success', 'Updated successfully');
	}
	public function bulkDelete(Request $request)
	{
		foreach ($request->groups as $group) {

			list($teacher_id, $class_id, $section_id) = explode('|', $group);

			\App\Models\TeacherAssignment::where([
				'teacher_id' => $teacher_id,
				'class_id' => $class_id,
				'section_id' => $section_id,
			])->delete();
		}

		return back()->with('success', 'Selected assignments deleted');
	}
}
