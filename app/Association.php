<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Association extends Model
{

    protected $visible = ['id','text'];

	protected $appends=['text'];

	protected $table = 'associations';

	public function getTextAttribute() {
		return $this->name;
	}
}
