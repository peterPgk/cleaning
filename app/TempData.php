<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TempData extends Model
{
    use SoftDeletes;
    //
    protected $fillable = ['uuid', 'data', 'company_id'];
    protected $visible = ['uuid','data','company_id'];

    /**
     * @param mixed $data
     */
    public static function add($data)
    {
        $data = (new static)->create([
            'uuid' => \Uuid::generate(),
            'data' => json_encode($data)
        ]);
        return $data->uuid;
    }

    /**
     * @param $uuid
     * @return TempData
     */
    public static function findByUuid($uuid)
    {
        return (new static)->where('uuid', '=', $uuid)->first();
    }

    /**
     * Return orders
     * @return Collection
     */
    public static function getOrders()
    {
        return (new static)->where('company_id', '!=', 0)->get();
    }

	/**
	 *
	 * @param int $days - Колко дни назад да гледаме
	 *
	 * @return Collection
	 */
	public static function getFeedbacksForDays($days = 3) {

    	$orders = self::getOrders();

    	return $orders->filter(function ($order) use($days) {

		    $order_data = $order->getData();
		    $order_date = Carbon::parse($order_data->service_date);

		    return (Carbon::now()->diffInDays($order_date)) >= (int)$days && (Carbon::now()->diffInDays($order_date)) <= ((int)$days+2);
	    });
    }

    /**
     * Return orders
     * @return type
     */
    public static function getUnfinshedOrders($start = null, $end = null)
    {
        $start = $start ?: Carbon::now()->toDateString() . ' 00:00:01';
        $end = $end ?: Carbon::now()->toDateString() . ' 23:59:59';
        return (new static)->where('company_id', '=', 0)
            ->where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->get();
    }


    /**
     * @param mixed $data
     * @return TempData
     */
    public function updateData($data)
    {

        /**
         * Това са данни, които се използват при прекъсване на регистрация,
         * не трябва да се презаписват
         */
        if (array_key_exists('ssrv', $data)) {
            unset($data['ssrv']);
        }

        $this->data = json_encode(array_merge((array)json_decode($this->data), (array)$data));
        return $this;
    }

    public function getData()
    {
        return json_decode($this->data, false, 2048);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function emails()
    {
        return $this->hasMany(Emails::class, 'tempdata_id');
    }

    public function isSendEmail($name) {

        $id = $this->id;
        $ret = false;
        $this->emails()->each(function (Emails $email) use ($name, $id,&$ret) {
            if ($email->isSend($name,$id)) {
                $ret = true;
            } else {
                $ret = false;
            }
        });
        return $ret;
    }
}
