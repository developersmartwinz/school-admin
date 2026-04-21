<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    //
	protected $fillable = [
		'student_id',
		'class_id',
		'section_id',
		'date',
		'status'
	];

	public function student()
	{
		return $this->belongsTo(\App\Models\User::class, 'student_id');
	}

	public function class()
	{
		return $this->belongsTo(\App\Models\SchoolClass::class, 'class_id');
	}

	public function section()
	{
		return $this->belongsTo(\App\Models\Section::class);
	}
}
