<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionElement extends Model
{
    protected $visible = ['name','info', 'pivot', 'plans'];

    public function getDefaultAttribute($default)
    {
        return (bool) $default;
    }

	public function plans()
	{
		return $this->belongsToMany(SubscriptionPlan::class, 'subscription_plans_elements')->withPivot(['value', 'text']);
	}

}
