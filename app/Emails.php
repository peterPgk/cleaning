<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Emails extends Model
{

    protected $fillable = ['tempdata_id', 'company_id', 'email_name'];
    protected $visible = ['tempdata_id', 'company_id', 'email_name'];


    public function getForPeriod($start = null, $end = null)
    {
        if (!$start) {
            $start = Carbon::now()->toDateString() . ' 00:00:01';
        }

        if (!$end) {
            $end = Carbon::now()->toDateString() . ' 23:59:59';
        }

        return $this->where('created_at', '>=', $start)->where('created_at', '<=', $end)->get();
    }

    public function isSend($emailName, $id, $start = null, $end = null)
    {
        if (!$start) {
            $start = Carbon::now()->toDateString() . ' 00:00:01';
        }

        if (!$end) {
            $end = Carbon::now()->toDateString() . ' 23:59:59';
        }

        return $this->where('created_at', '>=', $start)->where('created_at', '<=', $end)->
            where('email_name', $emailName)
            ->where('company_id',$id)
            ->orWhere('tempdata_id',$id)
            ->count();


    }

}
