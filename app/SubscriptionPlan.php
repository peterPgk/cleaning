<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $visible = ['stripe_plan','name','default','price', 'elements', 'new_value', 'pivot','show_help'];

//	protected $attributes = ['new_value'];
//	protected $appends = ['new_value'];

    public function getDefaultAttribute($default)
    {
        return (bool) $default;
    }

	public function elements()
	{
		return $this->belongsToMany(SubscriptionElement::class, 'subscription_plans_elements')->withPivot(['value', 'text']);
//		return $this->belongsToMany(SubscriptionElement::class, 'subscription_plans_elements');
	}

//	public function getNewValueAttribute($value) {
//		return $this->pivot->value == 1 && $this->pivot->text != "" ? $this->pivot->text : $this->pivot->value;
//	}
}
