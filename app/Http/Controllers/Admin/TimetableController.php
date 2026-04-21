<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timetable;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Teacher;
use App\Models\Subject;

class TimetableController extends Controller
{
    public function index(Request $request)
    {
        $query = Timetable::with(['class','section','teacher.user','subject']);

        // Filters
        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->day) {
            $query->where('day', $request->day);
        }

        $data = $query->orderBy('day')
            ->orderBy('start_time')
            ->paginate(10);

        $classes = SchoolClass::all();

        return view('admin.timetable.index', compact('data','classes'));
    }


    public function create()
    {
        $classes = SchoolClass::all();
        $teachers = Teacher::with('user')->get();

        return view('admin.timetable.create', compact('classes','teachers'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'section_id' => 'required',
            'teacher_id' => 'required',
            'subject_id' => 'required',
            'day' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        // 🔥 CONFLICT CHECK
        $exists = Timetable::where('day', $request->day)
            ->where(function($q) use ($request) {
                $q->where('teacher_id', $request->teacher_id)
                  ->orWhere(function($q2) use ($request) {
                      $q2->where('class_id', $request->class_id)
                         ->where('section_id', $request->section_id);
                  });
            })
            ->where(function($q) use ($request) {
                $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                  ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })
            ->exists();

        if ($exists) {
            return back()->with('error', 'Time conflict detected!');
        }

        Timetable::create($request->all());

        return redirect('/timetable')->with('success','Timetable added');
    }


    public function edit($id)
    {
        $data = Timetable::findOrFail($id);
        $classes = SchoolClass::all();
        $teachers = Teacher::with('user')->get();

        return view('admin.timetable.edit', compact('data','classes','teachers'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'class_id' => 'required',
            'section_id' => 'required',
            'teacher_id' => 'required',
            'subject_id' => 'required',
            'day' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $timetable = Timetable::findOrFail($id);

        // conflict check (exclude self)
        $exists = Timetable::where('id','!=',$id)
            ->where('day', $request->day)
            ->where(function($q) use ($request) {
                $q->where('teacher_id', $request->teacher_id)
                  ->orWhere(function($q2) use ($request) {
                      $q2->where('class_id', $request->class_id)
                         ->where('section_id', $request->section_id);
                  });
            })
            ->where(function($q) use ($request) {
                $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                  ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })
            ->exists();

        if ($exists) {
            return back()->with('error', 'Time conflict detected!');
        }

        $timetable->update($request->all());

        return redirect('/timetable')->with('success','Updated');
    }


    public function destroy($id)
    {
        Timetable::findOrFail($id)->delete();
        return back()->with('success','Deleted');
    }
public function grid(Request $request)
{
    $classes = \App\Models\SchoolClass::all();

    $class_id = $request->class_id;
    $section_id = $request->section_id;

    $sections = [];

    if ($class_id) {
        $sections = \App\Models\Section::where('class_id', $class_id)->get();
    }

    $data = [];

    // ✅ GET SLOTS FROM DB (DYNAMIC)
    if ($class_id) {

        $slots = \App\Models\TimeSlot::where('class_id', $class_id)
            ->orderBy('order')
            ->get();

        foreach ($slots as $slot) {

            $time = $slot->start_time . '-' . $slot->end_time;

            $data[$time] = [
                'type' => $slot->type, // class / break
                'days' => []
            ];

            foreach (['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $day) {
                $data[$time]['days'][$day] = null;
            }
        }
    }

    // ✅ MAP TIMETABLE DATA
    if ($class_id && $section_id) {

        $timetables = \App\Models\Timetable::with(['subject','teacher.user'])
            ->where('class_id', $class_id)
            ->where('section_id', $section_id)
            ->get();

        foreach ($timetables as $t) {

            $key = $t->start_time . '-' . $t->end_time;

            if (isset($data[$key])) {
                $data[$key]['days'][$t->day] = $t;
            }
        }
    }

    return view('admin.timetable.grid', compact(
        'classes',
        'sections',
        'class_id',
        'section_id',
        'data'
    ));
}
}