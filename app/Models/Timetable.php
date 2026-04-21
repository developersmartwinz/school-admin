<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    //
	// App\Models\Timetable.php

protected $fillable = [
    'class_id',
    'section_id',
    'teacher_id',
    'subject_id',
    'day',
    'start_time',
    'end_time'
];

public function class()
{
    return $this->belongsTo(\App\Models\SchoolClass::class);
}

public function section()
{
    return $this->belongsTo(\App\Models\Section::class);
}

public function teacher()
{
    return $this->belongsTo(\App\Models\Teacher::class);
}

public function subject()
{
    return $this->belongsTo(\App\Models\Subject::class);
}
}
