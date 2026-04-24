<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = [
        'title',
        'description',
        'notice_date',
        'notice_time',
        'sent_by',
        'attachment',
        'status',
        'created_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | Created By Relation
    |--------------------------------------------------------------------------
    */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}