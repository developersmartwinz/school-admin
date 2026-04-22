<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeePaymentItem extends Model
{
    protected $fillable = [
        'fee_payment_id',
        'student_fee_id',
        'amount_paid',
    ];

    /*
    |--------------------------------------------------------------------------
    | Parent Payment Relation
    |--------------------------------------------------------------------------
    */
    public function payment()
    {
        return $this->belongsTo(FeePayment::class, 'fee_payment_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Student Fee Relation
    |--------------------------------------------------------------------------
    */
    public function studentFee()
    {
        return $this->belongsTo(StudentFee::class);
    }
}