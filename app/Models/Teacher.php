<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    //
	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
	public function assignments()
	{
		return $this->hasMany(TeacherAssignment::class);
	}
}
