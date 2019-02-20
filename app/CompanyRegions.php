<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyRegions extends Model
{
    protected $fillable = ['company_id', 'code', 'price'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getByPrefix($code)
    {
        return $this->where('code','LIKE',$code.'%');
    }
}
