<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentFee extends Model
{
    protected $fillable = [
        'student_id',
        'fee_type_id',
        'amount',
        'year',
        
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feeType()
    {
        return $this->belongsTo(FeeType::class);
    }
	public function payments()
	{
		return $this->hasMany(FeePayment::class);
	}
}