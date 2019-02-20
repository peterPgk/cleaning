<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterSettigns extends Model
{
    //
    protected $fillable = ['name','value','setting_name'];
    protected $visible = ['id','name','value','setting_name'];

    public static function getByName($name)
    {
        return (new static)->where('setting_name',$name)->get()->first()->value;
    }
}
