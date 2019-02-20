<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Cashier\Billable;
use Illuminate\Http\Request;
use Laravel\Cashier\Subscription;

class Company extends Authenticatable
{
    use Notifiable;
    use Billable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'city', 'postcode', 'logo', 'liability_img', 'max_jobs', 'approved'
    ];

//    protected $visible = [
//        'id','email','name','created_at','pivot'
//    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function data()
    {
        return $this->hasOne(CompanyData::class);
    }

    public function associations()
    {
        return $this->hasManyThrough(Association::class, 'company_associations');
    }


    /**
     *
     * SERVICES
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'companies_services')->withPivot(['price', 'prices']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function serviceTypes()
    {
        return $this->belongsToMany(ServiceCategories::class, 'companies_service_categories');
    }

    /**
     * @return mixed
     */
    public function getCompanySelectedServices()
    {
        return $this->services()->with('categories')->with(['subs', 'parent.categories'])->get();
    }

    public function isFirstLogin()
    {
        return (bool)$this->data_collected;
    }

    public function isMainAccount()
    {
        return !(bool)$this->parent_id;
    }

    public function toggleApproved()
    {
        $this->approved = !$this->approved;
        return $this;
    }

    public function parent()
    {
        return $this->belongsTo(Company::class, 'parent_id', 'id');
    }

    public function subs()
    {
        return $this->hasMany(Company::class, 'parent_id');
    }

    public function createSubUser($email, $password)
    {
        $newUser = $this->replicate();
        $newUser->email = $email;
        $newUser->password = $password;
        $newUser->parent_id = $this->id;
        $newUser->remember_token = null;
        $newUser->save();

        return $newUser;
    }

    /**
     * @param Company|null $company
     * @return Company|null
     */
    public static function getMainAccount(Company $company = null)
    {
        if (!$company) {
            $company = \Auth::user();
        }

        if ($company->isMainAccount()) {
            return $company;
        }
        return $company->parent()->first();
    }


    public function timeslots()
    {
        return $this->hasMany(TimeSlot::class);
    }


    public function setPasswordAttribute($password)
    {
        $pass_alg = password_get_info($password);
        if(!$pass_alg['algo']) {
            $this->attributes['password'] = bcrypt($password);
        } else {
            $this->attributes['password'] = $password;
        }
    }

    public function regions()
    {
        return $this->hasMany(CompanyRegions::class);
    }

    public function orders()
    {
        return $this->hasMany(TempData::class);
    }

    public function todayOrders()
    {
        return $this->orders()->where('created_at', '>=', Carbon::today()->toDateString())->get();
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }


    /**
     *
     * POSTCODES
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function postcodes()
    {
        return $this->belongsToMany(Postcode::class)->withPivot('price');
    }

    /**
     * @param Request $request
     *
     * @return array|\Exception
     */
    public static function syncPostcodes(Request $request)
    {
        $data = [];

        foreach ($request->all() as $postcode_id => $price) {
            $data[$postcode_id] = ['price' => $price];
        }

        try {
            return self::getMainAccount()->postcodes()->sync($data);
        } catch (\Exception $e) {
            return $e;
        }

    }

    /**
     * Въведените пощенски кодове
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCompanyPostcodes()
    {
        return $this->postcodes()->get()->pluck('id');
    }


    /**
     * WORKDAYS
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function workdays()
    {
        return $this->hasOne(CompanyWorkdays::class, 'company_id', 'id');
    }

    /**
     * @param bool $json
     *
     * @return mixed
     */
    public function getCompanyWorkdays($json = false)
    {

        $workdays = $this->workdays()->get();

        $workdays = $workdays->isEmpty() ? '' : $workdays->pluck('workdays')->first();

        return (!!$json) ? json_decode($workdays) : $workdays;
    }


    /**
     * RESTDAYS/HOLIDAYS connection
     *
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function restdays()
    {
        return $this->belongsToMany(Holidays::class, 'company_restdays');
    }

    /**
     * Company restdays
     * @return \Illuminate\Support\Collection
     */
    public function getCompanyRestdays()
    {
        return $this->restdays()->get()->pluck('id');
    }

    /**
     * SUBSCRIPTIONS
     *
     * @return mixed
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class)->orderBy('created_at', 'desc');
    }

    public function getCompanySubscriptionPlan()
    {
        return SubscriptionPlan::where('stripe_plan', $this->subscriptions->pluck('name')->first())->first();
    }

    public function getSubscriptionLimit()
    {
        return $this->getCompanySubscriptionPlan()->jobs_limit;
    }

    public function getTodayBookings()
    {
        return $this->orders()->where('created_at', '>=', Carbon::now()->toDateString() . ' 00:00:01')->where('created_at', '<=', Carbon::now()->toDateString() . '23:59:59');
    }

    public function getThisMonthBookings()
    {
        return $this->orders()->where('created_at', '>=', Carbon::now()->startOfMonth())->where('created_at', '<=', Carbon::now()->endOfMonth());
        //TODO: Tuk trqbva da bade stripe datata ot stripe.
    }

    public function emails()
    {
        return $this->hasMany(Emails::class, 'company_id');
    }


    public static function getAllMainActive()
    {
        return (new static())->all()->where('parent_id', 0)->where('data_collected', 1);
    }

    public function isSendEmail($name)
    {
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

    public static function getCompanyByServices($services, $service_date, $postcode)
    {


        $needed = collect($services)->pluck('service_id')->toArray();

        $srv = Company::with(
            [
                'data',

                'ratings',

                'services' => function ($q) use ($services) {
                    $q->whereIn('services.id', collect($services)->pluck('service_id'));
                },

                'workdays',

                'restdays',

                'orders',

                'postcodes' => function ($q) use ($postcode) {

                    $q->whereRaw("INSTR('{$postcode}',`name`) = 1");
                },

            ]
        )->where('approved', 1)->get();

        $result = [];

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $srv->each(function ($firm) use (&$result, $services, $service_date, $needed, $startOfMonth, $endOfMonth) {

            /**
             * Има ли необходимите услуги
             */
            if ( $firm->services->isEmpty() ) return;

            /**
             * Работи ли в този пощенски код
             */
            if( $firm->postcodes->isEmpty() ) return;

            $aService = [];
            foreach ($firm->services as $service) {
                if (isset($service['id'])) {
                    $aService[] = $service['id'];
                }
            }

            if (count(array_intersect($needed, $aService)) != count($needed)) return;

            /**
             * Проверяваме дали фирмата работи през този ден от седмицата
             */
            if( !in_array(Carbon::parse($service_date)->dayOfWeek, json_decode($firm->workdays->workdays)) ) return;

            /**
             * Почивни дни
             */
            $restdays = $firm->restdays()->get()->pluck('date')->toArray();

            if( in_array(Carbon::parse($service_date)->toDateString(), $restdays) ) return;


            /**
             * Поръчките за месеца
             */
            $orders = collect($firm->orders)->filter(function ($order) use ($startOfMonth, $endOfMonth) {
                return Carbon::parse(json_decode($order->data)->service_date)->between($startOfMonth, $endOfMonth);
            })->count();

            if( (int)$orders >= (int)$firm->getSubscriptionLimit()) return;

            /**
             * Проверява поръчките за деня
             */
            $day_orders = $firm->todayOrders()->count();

            if( $day_orders >= (int)$firm->max_jobs ) return;


            /**
             *
             */
            $cleaning_quarantee = [
                '1' => '24 hours',
                '2' => '48 hours',
                '3' => '1 week',
                '4' => '2 weeks',
                '5' => '3 weeks',
                '6' => '4 weeks',
                '7' => 'No guarantee'
            ];

            $tmp = [
                'id' => (string)$firm->id,
                'name' => $firm->name,
                'logo' => $firm->logo,
                'year' => $firm->data->date_established,
                'region' => $firm->regions,
                'liability' => ( $firm->data->liability == 1 && Carbon::createFromFormat('Y-m-d', $firm->data->liability_expires)->gt(Carbon::now()) ),
                'cover' => '1 million',
                'rating' => (int)collect($firm->ratings)->avg('rating'),
                'total_reviews' => collect($firm->ratings)->count(),
                'ratings' => $firm->ratings,
                'quarentee' => $firm->data->complaints == 7 ? false : $cleaning_quarantee[$firm->data->complaints]
            ];

            /**
             * Поради новата логика за "n" броя стаи
             */
            $must_filter = false;

            $firm->services->each(function ($service, $k) use (&$tmp, $firm, $services, &$must_filter) {

                $count = collect($services)->where('service_id', $service->id)->first()['count'];


                /**
                 * Нова логика: ако фирмата не е въвела цена за "n" броя стаи, тогава не
                 * предлага услугата и трябва да се филтрира
                 */
//                $price = $service->prices_number > 1
//	                ? isset($a_prices[$count])
//		                ? $a_prices[$count]
//		                : ($service->pivot->price * $count)
//	                : ($service->pivot->price * $count);

                $price = $service->pivot->price * $count;

                if ( $service->prices_number > 1 && $count > 1 ) {
                    $a_prices = json_decode($service->pivot->prices, true);

                    if ( isset($a_prices[$count]) ) {
                        $price = $a_prices[$count];
                    }
                    else {
                        /**
                         * По новата логика фирмата не предлага услуга за толкова броя
                         * филтрираме я
                         */
                        $must_filter = true;
                        return;
                    }
                }

                $srv_tmp = [
                    'id' => (string)$service->id,
                    'price' => $price,
                    'name' => $service->name,
                    'count' => $count,
                    'sum_price' => $price
                ];

                $subs = Company::find($firm->id)->services()->whereIn('services.id', $service->subs->pluck('id'))->get();

                $additional = [];

                $subs->each(function ($sub) use (&$additional, $services) {
                    $additional[] = [
                        'id' => (string)$sub->id,
                        'name' => $sub->name,
                        'price' => $sub->pivot->price,
                    ];
                });
//                dd($additional);
                $srv_tmp['additional'] = $additional;

                $tmp['services'][$service->id] = $srv_tmp;
            });

            if ($must_filter) return;

            $result[] = $tmp;
            unset($tmp);
        });

        return $result;
    }

    public static function getCompanyWithLowestPrice($services, $service_date, $postcode) {
        $companies = static::getCompanyByServices($services,$service_date,$postcode);
        $sorted = collect($companies)->sortBy(function(&$company,$key){
            $total_sum = 0;
            foreach ($company['services'] as $service_id => $data) {
                $total_sum += $data['sum_price'];
            }
            return $total_sum;
        });
        return $sorted->first();
    }

    public static function getLowestPrice($company_data) {
        return collect($company_data['services'])->sum(function($service) {
           return $service['sum_price'];
        });
    }

}
