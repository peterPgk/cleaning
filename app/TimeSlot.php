<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $fillable = [
        'from',
        'to',
        'available'
    ];


    protected $visible = ['id','from','to','available'];

    public function getAvailableAttribute($val)
    {
        return (bool)$val;
    }

//    public function getFromAttribute($value)
//    {
//        return Carbon::parse($value)->toIso8601String();
//    }
//
//    public function getToAttribute($value)
//    {
//        return Carbon::parse($value)->toIso8601String();
//    }
}
