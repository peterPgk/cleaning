<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{

    protected $fillable = ['id','name','limit'];
//    protected $visible = ['id','name','limit','created_at'];
    //
    public function categories()
    {
       return $this->belongsTo(ServiceCategories::class,'service_category_id');
    }


    public function getMainServices()
    {
       return $this->where('parent_id',0);
    }

    public function parent()
    {
        return $this->belongsTo(Service::class,'parent_id','id');
    }

    public function subs()
    {
        return $this->hasMany(Service::class,'parent_id');
    }

    public function related() {
        return $this->hasMany(Service::class,'related_id');
    }

    public function related_main()
    {
        return $this->belongsTo(Service::class,'related_id','id');
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class,'companies_services')->withPivot('price');
    }

	public function extended() {
		return $this->hasMany(ServicesExtended::class, 'id', 'service_id');
    }
}
