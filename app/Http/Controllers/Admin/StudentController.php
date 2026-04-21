<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Section;

class StudentController extends Controller
{
    // ✅ LISTING + SEARCH + PAGINATION
    public function index(Request $request)
    {
        $query = Student::with(['user', 'class', 'section']);

        if ($request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        $students = $query->latest()->paginate(10);

        return view('admin.students.index', compact('students'));
    }

    // ✅ CREATE PAGE
    public function create()
    {
        $classes = SchoolClass::all();
        $sections = Section::all();

        return view('admin.students.create', compact('classes', 'sections'));
    }

    // ✅ STORE STUDENT
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users,phone',
            'email' => 'required|unique:users,email',
			'father_phone' => 'nullable|digits_between:8,15',
			'mother_phone' => 'nullable|digits_between:8,15',
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'role' => 'student'
        ]);

        // Create student
        Student::create([
            'user_id' => $user->id,
            'reg_no' => $request->reg_no,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'house' => $request->house,
            'category' => $request->category,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'blood_group' => $request->blood_group,
            'aadhar' => $request->aadhar,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
			
			// ✅ Father
			'father_name' => $request->father_name,
			'father_phone' => $request->father_phone,
			'father_email' => $request->father_email,
			'father_occupation' => $request->father_occupation,
			'father_qualification' => $request->father_qualification,
			'father_designation' => $request->father_designation,

			// ✅ Mother
			'mother_name' => $request->mother_name,
			'mother_phone' => $request->mother_phone,
			'mother_email' => $request->mother_email,
			'mother_occupation' => $request->mother_occupation,
			'mother_qualification' => $request->mother_qualification,
			'mother_designation' => $request->mother_designation,
        ]);

        return redirect('/students')->with('success', 'Student created');
    }

    // ✅ EDIT PAGE
    public function edit($id)
    {
        $student = Student::with('user')->findOrFail($id);
        $classes = SchoolClass::all();
        $sections = Section::all();

        return view('admin.students.edit', compact('student', 'classes', 'sections'));
    }

    // ✅ UPDATE
    public function update(Request $request, $id)
    {
        $student = Student::with('user')->findOrFail($id);

        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users,phone,' . $student->user_id,
        ]);

        // Update user
        $student->user->update([
            'name' => $request->name,
            'phone' => $request->phone
        ]);

        // Update student
        $student->update([
            'reg_no' => $request->reg_no,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'house' => $request->house,
            'category' => $request->category,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'blood_group' => $request->blood_group,
            'aadhar' => $request->aadhar,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
        ]);

        return redirect('/students')->with('success', 'Student updated');
    }

    // ✅ DELETE SINGLE
    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        // delete user (student auto linked)
        $student->user()->delete();

        return back()->with('success', 'Student deleted');
    }

    // ✅ BULK DELETE
    public function bulkDelete(Request $request)
    {
        $students = Student::whereIn('id', $request->ids)->get();

        foreach ($students as $student) {
            $student->user()->delete();
        }

        return back()->with('success', 'Selected students deleted');
    }
}