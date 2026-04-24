<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Homework;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class HomeworkController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Homework List
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = Homework::with([
            'class',
            'section',
            'subject',
            'teacher.user',
        ])->latest();

        if ($request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        $homeworks = $query->paginate(10);

        $classes = SchoolClass::orderBy('name')->get();
		$teachers = User::where('role', 'teacher')
        ->orderBy('name')
        ->get();

        return view(
            'admin.homeworks.index',
            compact('homeworks', 'classes','teachers')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create Page
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $classes = SchoolClass::orderBy('name')->get();

        $teachers = Teacher::with('user')
            ->orderBy('id', 'desc')
            ->get();

        return view(
            'admin.homeworks.create',
            compact('classes', 'teachers')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store Homework
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'type' => 'required',
            'class_id' => 'required',
            'section_id' => 'required',
            'subject_id' => 'required',
            'teacher_id' => 'required',
            'assign_date' => 'required|date',
            'assign_time' => 'nullable',
            'submission_date' => 'nullable|date',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'status' => 'required',
            'description' => 'nullable',
        ]);

        $attachment = null;

        if ($request->hasFile('attachment')) {
            $attachment = time() . '_' .
                $request->file('attachment')->getClientOriginalName();

            $request->file('attachment')->move(
                public_path('uploads/homeworks'),
                $attachment
            );
        }

        Homework::create([
            'title' => $request->title,
            'type' => $request->type,
            'description' => $request->description,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'assign_date' => $request->assign_date,
            'assign_time' => $request->assign_time,
            'submission_date' => $request->submission_date,
            'attachment' => $attachment,
            'status' => $request->status,
            'created_by' => auth()->id(),
        ]);

        return redirect('/homeworks')
            ->with('success', 'Homework created successfully');
    }

/*
|--------------------------------------------------------------------------
| Edit Page
|--------------------------------------------------------------------------
*/
public function edit($id)
{
    $homework = Homework::findOrFail($id);

    /*
    |--------------------------------------------------------------------------
    | Classes
    |--------------------------------------------------------------------------
    */
    $classes = SchoolClass::orderBy('name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Sections by selected class
    |--------------------------------------------------------------------------
    */
    $sections = Section::where(
        'class_id',
        $homework->class_id
    )
    ->orderBy('name')
    ->get();

    /*
    |--------------------------------------------------------------------------
    | Subjects by Class + Section
    |--------------------------------------------------------------------------
    | Uses teacher_assignments table
    |--------------------------------------------------------------------------
    */
    $subjects = \App\Models\TeacherAssignment::with('subject')
        ->where('class_id', $homework->class_id)
        ->where('section_id', $homework->section_id)
        ->get()
        ->map(function ($row) {
            return $row->subject;
        })
        ->unique('id')
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Teachers by Class + Section + Subject
    |--------------------------------------------------------------------------
    | Only correct teachers should show
    |--------------------------------------------------------------------------
    */
    $teachers = \App\Models\TeacherAssignment::with('teacher.user')
        ->where('class_id', $homework->class_id)
        ->where('section_id', $homework->section_id)
        ->where('subject_id', $homework->subject_id)
        ->get()
        ->map(function ($row) {
            return $row->teacher;
        })
        ->unique('id')
        ->values();

    return view(
        'admin.homeworks.edit',
        compact(
            'homework',
            'classes',
            'sections',
            'subjects',
            'teachers'
        )
    );
}

    /*
    |--------------------------------------------------------------------------
    | Update Homework
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $homework = Homework::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'type' => 'required',
            'class_id' => 'required',
            'section_id' => 'required',
            'subject_id' => 'required',
            'teacher_id' => 'required',
            'assign_date' => 'required|date',
            'assign_time' => 'nullable',
            'submission_date' => 'nullable|date',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'status' => 'required',
            'description' => 'nullable',
        ]);

        $attachment = $homework->attachment;

        if ($request->hasFile('attachment')) {
            $attachment = time() . '_' .
                $request->file('attachment')->getClientOriginalName();

            $request->file('attachment')->move(
                public_path('uploads/homeworks'),
                $attachment
            );
        }

        $homework->update([
            'title' => $request->title,
            'type' => $request->type,
            'description' => $request->description,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'assign_date' => $request->assign_date,
            'assign_time' => $request->assign_time,
            'submission_date' => $request->submission_date,
            'attachment' => $attachment,
            'status' => $request->status,
        ]);

        return redirect('/homeworks')
            ->with('success', 'Homework updated successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Single
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        Homework::findOrFail($id)->delete();

        return back()->with(
            'success',
            'Homework deleted successfully'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Bulk Delete
    |--------------------------------------------------------------------------
    */
    public function bulkDelete(Request $request)
    {
        if ($request->ids) {
            Homework::whereIn(
                'id',
                $request->ids
            )->delete();
        }

        return back()->with(
            'success',
            'Selected homeworks deleted successfully'
        );
    }
}