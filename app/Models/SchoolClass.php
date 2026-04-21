<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    //
	protected $fillable = [
        'name',
        
    ];
	public function classSubjects()
	{
		return $this->hasMany(\App\Models\ClassSubject::class, 'class_id');
	}
}
