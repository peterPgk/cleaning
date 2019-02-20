<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    protected $fillable = ['question', 'answer'];

    public function category()
    {
        $this->hasOne(ServiceCategories::class, 'service_category_id');
    }
}