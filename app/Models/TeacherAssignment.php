<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherAssignment extends Model
{
    //
	// TeacherAssignment.php
	protected $fillable = [
		'teacher_id',
		'class_id',
		'section_id',
		'subject_id'
	];
	/* public function teacher()
	{
		return $this->belongsTo(Teacher::class);
	}

	public function class()
	{
		return $this->belongsTo(\App\Models\SchoolClass::class);
	}

	public function section()
	{
		return $this->belongsTo(\App\Models\Section::class);
	} */
	
	public function teacher()
	{
		return $this->belongsTo(\App\Models\Teacher::class);
	}

	public function class()
	{
		return $this->belongsTo(\App\Models\SchoolClass::class, 'class_id');
	}

	public function section()
	{
		return $this->belongsTo(\App\Models\Section::class);
	}

	public function subject()
	{
		return $this->belongsTo(\App\Models\Subject::class, 'subject_id');
	}
}
