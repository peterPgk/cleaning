<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $visible=['id','email','rating','date_created','comment','answer','completed','uuid'];
    protected $fillable = ['email','rating','date_created','comment','answer','completed','uuid'];
    protected $appends = ['date_created'];
    protected $casts = ['completed'=>'boolean','id'=>'string'];
    public function getDateCreatedAttribute()
    {
        return Carbon::parse($this->created_at)->format('Y/m/d');
    }
}
