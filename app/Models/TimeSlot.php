<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    //
	public function class()
{
    return $this->belongsTo(\App\Models\SchoolClass::class, 'class_id');
}
}
