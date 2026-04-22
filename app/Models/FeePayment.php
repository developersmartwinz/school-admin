<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeePayment extends Model
{
    protected $fillable = [
        'student_fee_id',
        'receipt_no',
        'paid_amount',
        'payment_mode',
        'transaction_id',
        'student_id',
        'payment_date',
        'remarks',
        'status',
    ];

    public function studentFee()
    {
        return $this->belongsTo(StudentFee::class);
    }
	/*
    |--------------------------------------------------------------------------
    | Student Relation
    |--------------------------------------------------------------------------
    */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Payment Items Relation
    |--------------------------------------------------------------------------
    */
    public function items()
    {
        return $this->hasMany(FeePaymentItem::class);
    }
}