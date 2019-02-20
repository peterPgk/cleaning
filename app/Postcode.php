<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Postcode extends Model
{
//    protected $visible = ['id', 'name', 'selected', 'price'];
    protected $visible = ['id', 'text'];
    protected $fillable = ['name'];
//    protected $appends = ['selected', 'price'];
    protected $appends = ['text'];

	public function companies() {
		return $this->belongsToMany(Company::class)->withPivot('price');
	}

	/**
	 * @return bool
	 */
//	public function getSelectedAttribute() {
//		return count($this->companies) > 0;
//	}
//
//	/**
//	 * @return int
//	 */
//	public function getPriceAttribute() {
//		return $this->selected ?  $this->companies[0]->pivot->price : 0;
//	}

	public function getTextAttribute() {
		return $this->name;
	}

}
