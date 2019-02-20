<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holidays extends Model
{

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','date'
    ];

    protected $dates = [
    	'created_at',
    	'updated_at',
	    'date'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
    protected $visible = ['id', 'text','date'];

    protected $table = 'holidays';

	protected $appends = ['text'];

	public function getTextAttribute() {
		return $this->name . ' (' . $this->date->format('d.m.Y') . ')';
	}

}
