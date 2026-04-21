<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolClass;

class ClassController extends Controller
{
	public function index()
{
    $classes = \App\Models\SchoolClass::latest()->paginate(10);

    return view('admin.classes.index', compact('classes'));
}
public function create()
{
    return view('admin.classes.create');
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required'
    ]);

    SchoolClass::create([
        'name' => $request->name
    ]);

    return redirect()->back()->with('success', 'Class created');
}
public function edit($id)
{
    $class = \App\Models\SchoolClass::findOrFail($id);
    return view('admin.classes.edit', compact('class'));
}

public function update(Request $request, $id)
{
    $class = \App\Models\SchoolClass::findOrFail($id);
    $class->update([
        'name' => $request->name
    ]);

    return redirect('/classes')->with('success', 'Class updated');
}

public function destroy($id)
{
    \App\Models\SchoolClass::findOrFail($id)->delete();
    return back()->with('success', 'Class deleted');
}

public function bulkDelete(Request $request)
{
    \App\Models\SchoolClass::whereIn('id', $request->ids)->delete();
    return back()->with('success', 'Selected classes deleted');
}
}
