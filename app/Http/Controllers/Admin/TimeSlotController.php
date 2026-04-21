<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TimeSlot;
use App\Models\SchoolClass;

class TimeSlotController extends Controller
{
    public function index()
    {
        $data = TimeSlot::with('class')->orderBy('order')->paginate(10);
        return view('admin.timeslots.index', compact('data'));
    }

    public function create()
    {
        $classes = SchoolClass::all();
        return view('admin.timeslots.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'type' => 'required',
            'order' => 'required'
        ]);

        TimeSlot::create($request->all());

        return redirect('/time-slots')->with('success','Created');
    }

    public function edit($id)
    {
        $data = TimeSlot::findOrFail($id);
        $classes = SchoolClass::all();

        return view('admin.timeslots.edit', compact('data','classes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'class_id' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'type' => 'required',
            'order' => 'required'
        ]);

        TimeSlot::findOrFail($id)->update($request->all());

        return redirect('/time-slots')->with('success','Updated');
    }

    public function destroy($id)
    {
        TimeSlot::findOrFail($id)->delete();
        return back()->with('success','Deleted');
    }

    public function bulkDelete(Request $request)
    {
        TimeSlot::whereIn('id', $request->ids)->delete();
        return back()->with('success','Deleted selected');
    }
}