<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServicesExtended extends Model
{

	protected $table = 'companies_services_extended';

//    protected $fillable = ['id','name','limit'];
//    protected $visible = ['id','name','limit','created_at'];
    //

	public function service() {
		return $this->belongsTo(Service::class, 'service_id' );
	}
}
