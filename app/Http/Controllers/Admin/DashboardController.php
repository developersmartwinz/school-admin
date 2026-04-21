<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Timetable;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'students' => User::where('role','student')->count(),
            'teachers' => Teacher::count(),
            'classes' => SchoolClass::count(),
            'sections' => Section::count(),
            'subjects' => Subject::count(),
            'timetable' => Timetable::count(),
        ];

        // Sample chart data (can improve later)
        $chart = [
            'labels' => ['Mon','Tue','Wed','Thu','Fri','Sat'],
            'values' => [5,8,6,7,9,4],
        ];

        return view('dashboard', compact('data','chart'));
    }
}
