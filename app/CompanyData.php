<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyData extends Model
{
    protected $fillable = ['id','company_id','name','trading_name', 'client_name', 'company_number','vat','phone','phone_2', 'city', 'postcode',
                            'website','address','address_2','address_3','description','youtube', 'complaints','date_established','liability','liability_amount',
                            'liability_certificate','liability_expires', 'members_of','created_at','updated_at','deleted_at'];

    protected $appends = ['email'];

    public function company()
    {        
        return $this->belongsTo(Company::class);
    }

    public function getEmailAttribute()
    {
        return $this->company()->get()->first()->email;
    }

    public function setLiabilityAmountAttribute($value)
    {
        $this->attributes['liability_amount'] = empty($value) ? 0 : $value;
    }

    public function setLiabilityExpiresAttribute($value)
    {
        $this->attributes['liability_expires'] = empty($value) ? null : $value;
    }
}
