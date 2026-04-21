<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //
	protected $fillable = [
    'user_id',
    'reg_no',
    'dob',
    'gender',
    'house',
    'category',
    'address',
    'city',
    'state',
    'blood_group',
    'aadhar',
    'class_id',
    'section_id',
	
	'father_name','father_phone','father_email',
    'father_occupation','father_qualification','father_designation',

    'mother_name','mother_phone','mother_email',
    'mother_occupation','mother_qualification','mother_designation',
];
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function class()
	{
		return $this->belongsTo(SchoolClass::class);
	}

	public function section()
	{
		return $this->belongsTo(Section::class);
	}
}
