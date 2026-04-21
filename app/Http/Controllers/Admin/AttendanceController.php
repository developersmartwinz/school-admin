<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\User;

class AttendanceController extends Controller
{
	public function index(Request $request)
	{
		$classes = \App\Models\SchoolClass::all();

		$query = \App\Models\Attendance::with(['student','class','section']);

		if ($request->date) {
			$query->where('date', $request->date);
		}

		if ($request->class_id) {
			$query->where('class_id', $request->class_id);
		}

		if ($request->section_id) {
			$query->where('section_id', $request->section_id);
		}

		$data = $query->latest()->paginate(10);

		return view('admin.attendance.index', compact('data','classes'));
	}

	public function create(Request $request)
	{
		$classes = \App\Models\SchoolClass::all();

		return view('admin.attendance.create', compact('classes'));
	}


	public function getStudents($class_id, $section_id, $date)
	{
		// ✅ get students from students table
		$students = \App\Models\Student::with('user')
			->where('class_id', $class_id)
			->where('section_id', $section_id)
			->get();

		// ✅ existing attendance
		$attendance = \App\Models\Attendance::where('class_id', $class_id)
			->where('section_id', $section_id)
			->where('date', $date)
			->get()
			->keyBy('student_id');

		// ✅ format response
		$studentsFormatted = $students->map(function ($s) {
			return [
				'id' => $s->user->id,     // important
				'name' => $s->user->name
			];
		});

		return response()->json([
			'students' => $studentsFormatted,
			'attendance' => $attendance
		]);
	}


	public function store(Request $request)
	{
		foreach ($request->students as $student_id => $status) {

			\App\Models\Attendance::updateOrCreate(
				[
					'student_id' => $student_id,
					'date' => $request->date
				],
				[
					'class_id' => $request->class_id,
					'section_id' => $request->section_id,
					'status' => $status
				]
			);
		}

		return back()->with('success','Attendance saved');
	}
}
