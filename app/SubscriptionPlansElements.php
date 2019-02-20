<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlansElements extends Model
{
//    protected $visible = ['value', 'text'];

	protected $attributes = ['test'];
	protected $appends = ['test'];

    protected $table = 'subscription_plans_elements';

	public function getTestAttribute($value) {
		return 'Test';
	}

//	public function plans()
//	{
//		return $this->belongsToMany(SubscriptionPlan::class, 'subscription_plans_elements')->withPivot('value', 'text');
////		return $this->belongsToMany(SubscriptionPlan::class, 'subscription_plans_elements');
//	}
}
