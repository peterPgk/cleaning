<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyWorkdays extends Model
{

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'workdays'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $table = 'company_workdays';




}
