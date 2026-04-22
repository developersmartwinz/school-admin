<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeType extends Model
{
    protected $fillable = [
        'name',
        'amount',
        'frequency',
        'is_optional',
        'description',
    ];
	
	public function studentFees()
	{
		return $this->hasMany(StudentFee::class);
	}
}