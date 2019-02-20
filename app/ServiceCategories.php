<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCategories extends Model
{
    use SoftDeletes;
    protected $table = 'service_categories';
//    protected $visible = ['id','name','description','created_at','services'];
    protected $fillable = ['name','description'];

    public function childs()
    {
        return $this->hasMany(ServiceCategories::class,'parent_id');
    }

    public function parents()
    {
       return $this->belongsTo(ServiceCategories::class,'parent_id');
    }

    public function mainCategories()
    {
        return $this->where('parent_id',0);
    }

    public function services()
    {
       return $this->hasMany(Service::class,'service_category_id');
    }

    public function questions()
    {
        return $this->hasMany(Questions::class,'service_category_id');
    }

	/**
	 * @param $query
	 * @param $ids
	 *
	 * @return mixed
	 */
	public function scopeRootCategories( $query, $ids ) {

		return $query->with('parents')->whereIn('id', $ids);
    }

	/**
	 * @return mixed
	 */
	public static function getRootCategoriesSelect() {
		$result = [];
		$services = static::where('parent_id', '=', 0)->where('is_extra', 0)->get();

		foreach ($services as $service) {

			$result[] = [
				'id' => $service->id,
				'text' => $service->name
			];
		}

		return $result;
    }

	public static function getRootCategoriesNames() {
		return static::where('parent_id', 0)->pluck('name', 'id')->toArray();
    }

}
