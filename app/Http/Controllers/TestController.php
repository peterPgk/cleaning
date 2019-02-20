<?php

namespace App\Http\Controllers;

use App\Company;
use App\Mail\ClientBookingEmail;
use App\Mail\CompanyBookingEmail;
use App\Mail\CountLimit;
use App\Mail\LimitExpireEmail;
use App\MasterSettigns;
use App\Service;
use App\ServiceCategories;
use App\TempData;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\Stripe;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

//	    $restore_data = TempData::findByUuid('39fae1f0-9872-11e6-b32d-a12080d15009')->getData();
//	    $restore_data = TempData::findByUuid('d1a87f30-986d-11e6-ae1e-61dabecfe56b')->getData();

//        $user = Company::find(56);
//        $user->createSubUser('ivan2@abv.bg','321312');
//        dd(Company::getMainAccount(Company::find(3))->toArray());

//        return view('test.index', compact('restore_data'));

        return view('test.index');
    }

	/**
	 * @param Request $request
	 *
	 * @return string
	 */
	public function stepTwo( Request $request ) {

		$r = collect($request->all())->except('ssrv')->filter();

		$order = TempData::findByUuid($r->get('uuid'));

		/**
		 * ъпдейтвам вместо да презапиша, защото губя
		 * 'services' параметъра - основната категория
		 */
		$order->updateData($r->toArray())->save();

//		return json_encode(['share' => $order->getData()]);
		return json_encode(['share' => $r]);
    }


	/**
	 * @param Request $request
	 *
	 * @return string
	 */
	public function stepThree( Request $request ) {

		$r = collect($request->all())->except('ssrv')->filter();

		$order = TempData::findByUuid($r->get('uuid'));

		/**
		 * Ако това е стъпка за продължаване на букинг,
		 * трябва да изчистим ако има предишни услуги, защото иначе не
		 * дава възможност за смяна на услугите
		 */
		$order->update(['data' => json_encode($r->toArray())]);
//		$order->updateData($r->toArray())->save();

		return json_encode(['share' => $r]);
	}


    /**y
     * Return all companies,
     *
     * @param Request $request - chosen services
     *
     * @return string
     */
    public function stepFour(Request $request) {

	    $r = collect($request->all())->except('ssrv')->filter();

	    $order = TempData::findByUuid($r->get('uuid'));

	    $order->update(['data' => json_encode($r->toArray())]);
//	    $data->updateData($r->toArray())->save();

	    return json_encode(['share' => $r]);
    }


    /**
     * Chosen company
     *
     * @param Request $request
     *
     * @return string
     */
    public function stepFive(Request $request)
    {

	    /**
	     * Това ssrv май беше за запазване на всички данни за да може при ресет да се попълнят данните
	     */
	    $r = collect($request->all())->except('ssrv')->filter()->toArray();

	    $client = [
		    'firstname' => $r['firstname'],
		    'lastname' => $r['lastname'],
		    'email' => $r['email']
	    ];

	    /**
	     * TODO: Информация за потребителя в поръчката
	     * Да запазя само информацията за потребителя като масив, както е по-горе.
	     * В момента след като се върне към фронтенда чрез share после се записва с този ключ,
	     * и се дублират данните за потребителя.
	     * Да проверя къде използвам директно извикване на firstname ... и да го заменя с
	     * client['firstname']...
	     *
	     */
        TempData::findByUuid($request->get('uuid'))->updateData($r)->save();


	    /**
	     * Вземаме буквите на кода
	     */
	    preg_match('/(^[A-Za-z]+)/', $r['postcode'], $postcode_string);

	    /**
         *
         * Копирано от предната step 4
         *
         */
//	    $timeslot = null;
//	    $timeslot_from = null;
//	    $timeslot_to = null;
//	    $postcode = $r['postcode'];
	    $postcode = $postcode_string[0];
	    $service_date = $r['service_date'];
	    $services = [];

	    foreach ($r as $k => $item) {
		    if (strpos($k, 'services_') !== false) {
			    $services[] = ['service_id' => substr($k, '9'), 'count' => (int)$item];
		    }
	    }

	    $selected_services = collect($r)->filter(function ($s, $s_k) {
		    return (strpos($s_k, 'services') !== false);
	    });

	    $companies = $this->getCompanyByServices($services, $service_date, $postcode);

	    return json_encode(['store' => ['companies' => $companies], 'share' => ['uuid' => $request->get('uuid'), 'selected_services' => $selected_services, 'client' => $client ]]);
    }

	/**
	 * USER INFO
	 *
	 * @param Request $request
	 *
	 * @return string
	 */
	public function stepSix( Request $request ) {

		/**
		 * Запазваме избраната компания. След това ще я предадем към страйп стъпката през Stripe компонента,
		 */
		return json_encode( [ 'share' => [ 'company' => $request->all() ] ] );
	}

	/**
	 * STRIPE PAYMENTS
	 *
	 * @param Request $request
	 *
	 * @return string
	 * @throws \Exception
	 */
    public function stepSeven(Request $request)
    {

	    $data = $request->all();

	    $order = TempData::findByUuid($request->get('uuid'));

	    /**
	     * Ако поръчката вече има прикачена компания, значи е платена
	     * Минаваме на следващата стъпка
	     * TODO: Има ли начин да проверим и в страйпа
	     *
	     */
	    if ($order->company_id != 0) {
		    return json_encode(['status' => 'ok']);
	    }

	    /**
	     * Owner fee
	     */
        $booking_fee = MasterSettigns::where('setting_name', 'booking')->get()->first();

        $selected_company = $data['company'];

	    /**
	     * Selected services with price from chosen company
	     */
        $company_id = $selected_company['id'];

//        throw new \Exception(json_encode($request->all()['company']['region'][0]['price']));
//        $region_price = isset($selected_company['region'][0]['price']) ? $selected_company['region'][0]['price'] : 0;

//        $total_sum = (collect($selected_company['services'])->sum('sum_price') + $region_price) * 100; //in cents
        $total_sum = (collect($selected_company['services'])->sum('sum_price')) * 100; //in cents
        $sum = round($total_sum * $booking_fee['value'] / 100); //booking fee
        Stripe::setApiKey(env('STRIPE_KEY'));
        $token = $request->get('id');

	    /**
	     * Strype
	     */
        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => $sum, // Amount in cents
                "currency" => "gbp",
                "source" => $token,
                "description" => "Booking fee for request " . $request->get('uuid')
            ));

        } catch (\Stripe\Error\Card $e) {
            throw new \Exception('Declined card');
        }

		$order->updateData(['company' => $selected_company, 'client' => $data['client'], 'site_charge' => $charge->amount ]);

	    /**
	     * $order->update(['company_id', $company_id]) не действа !§€@§€
	     */
		$order->company_id = $company_id;
		$order->save();

//      $order->update(['company_id',$order_data->company->id]);
//	    $order->update(['company_id', 56]);

	    $order_data = $order->getData();

        $company = Company::find($company_id);

        \Mail::to($order_data->email)->send(new ClientBookingEmail($order_data,$total_sum,$sum,$company));
        \Mail::to($company->email)->send(new CompanyBookingEmail($order,$order_data,$total_sum,$sum));


        $limit = $company->getSubscriptionLimit();
        $today_orders = $company->getThisMonthBookings()->count();

    if($limit - $today_orders <= 0) {
            \Mail::to($company->email)->send(new LimitExpireEmail($company));
        }

        if($limit - $today_orders == (int)$limit/2) {
            \Mail::to($company->email)->send(new CountLimit((int)$limit - $today_orders));
        }


        return json_encode(['status' => 'ok']);
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function getSubcategories(Request $request)
    {

        $service_category_id = $request->get('services');

        $serviceCategories = ServiceCategories::find($service_category_id);

//        $subCat = $serviceCategories->childs()->with('services.related')->get();

	    /**
	     * Филтрираме кей колектион
	     */
	    $subCat = $serviceCategories
		    ->childs()
		    ->with(['services' => function ($query) {
		            $query->whereNull('is_additional');
	            }, 'services.related'])
		    ->get();

	    /**
	     * Тук вземаме кей колекта. За сега няма да го обвързвам с категория
	     */
//	    $extra = Service::whereNotNull('is_additional')->get();

	    $extra = $serviceCategories
		    ->childs()
		    ->with(['services' => function ($query) {
			    $query->whereNotNull('is_additional');
		    }])
		    ->get()
		    ->pluck('services')
	        ->filter(function ($service) {
	        	return !$service->isEmpty();
	        })
	        ->first();

        $result = ['id' => $serviceCategories->id, 'text' => $serviceCategories->name, 'extra_services' => $extra, 'is_select' => $serviceCategories->is_select, 'additional' => []];

        $subCat->each(function ($v, $k) use (&$result) {
            $result['additional'][$v->name]['data'] = $v->services;
            $result['additional'][$v->name]['is_extra'] = !!$v->is_extra;
            $result['additional'][$v->name]['name'] = $v->name;
        });

	    /**
	     * Ако е ресторе стъпка в рекуеста ще бъде ид-то на датата, която възстановяваме
	     */
        $uuid = $request->has('uuid') ? $request->get('uuid') : TempData::add($request->all());
//        $uuid = TempData::add($request->all());
//        return json_encode(['store' => ['services' => [$result]],'share'=> ['uuid'=> (string)$uuid]]);
        return json_encode(['share' => ['uuid' => (string)$uuid, 'services' => $service_category_id, 'ssrv' => [$result]]]);
    }

    public function testMethod()
    {
        $s = Service::find(2)->companies()->get();
        return $s;
    }

    public function getCompanyTemp() {

    	$ttt = TempData::getFeedbacksForDays(3);
    	dd($ttt);


	    //preg_match('/(^[A-Za-z]+)/', "NW89755", $postcode_string);

    	$postcode = $postcode_string[0];
//		dd($postcode);

	    $services = [
	    	'services_13' => 2,
		    'services_17' => 2
	    ];

	    $service_date = '2017-01-25T00:00:00+02:00';

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
				    $q->whereRaw("INSTR('{$postcode}',`name`) = 1 ");
			    },
		    ]
	    )->where('approved', 1)->get();

//	    dd($srv);

	    $result = [];

	    $startOfMonth = Carbon::now()->startOfMonth();
	    $endOfMonth = Carbon::now()->endOfMonth();

	    $srv->each(function ($firm) use (&$result, $services, $service_date, $startOfMonth, $endOfMonth) {

		    /**
		     * Проверяваме дали фирмата работи през този ден от седмицата
		     */
//		    if( !in_array(Carbon::parse($service_date)->dayOfWeek, json_decode($firm->workdays->workdays)) ) return;
		    /**
		     * TODO: restdays
		     */

		    /**
		     * Orders - Може ли да се направи през query-то
		     */
//		    $orders = collect($firm->orders)->filter(function ($order) use ($startOfMonth, $endOfMonth) {
//			    return Carbon::parse(json_decode($order->data)->service_date)->between($startOfMonth, $endOfMonth);
//		    })->count();
//
//		    if( $orders >= (int)$firm->max_jobs ) return;

		    if($firm->postcodes->isEmpty()) return;

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
//			    'timeslots' => $firm->timeslots,
			    'region' => $firm->regions,
			    'liability' => ( $firm->data->liability == 1 && Carbon::createFromFormat('Y-m-d', $firm->data->liability_expires)->gt(Carbon::now()) ),
			    'cover' => '1 million',
			    'rating' => (int)collect($firm->ratings)->avg('rating'),
			    'total_reviews' => collect($firm->ratings)->count(),
			    'ratings' => $firm->ratings,
			    'quarentee' => $firm->data->complaints == 7 ? false : $cleaning_quarantee[$firm->data->complaints]
		    ];

		    $firm->services->each(function ($service, $k) use (&$tmp, $firm, $services) {

			    $count = collect($services)->where('service_id', $service->id)->first()['count'];
			    $srv_tmp = [
				    'id' => (string)$service->id,
				    'price' => $service->pivot->price,
				    'name' => $service->name,
				    'count' => $count,
				    'sum_price' => $count * $service->pivot->price
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
			    $srv_tmp['additional'] = $additional;

			    $tmp['services'][$service->id] = $srv_tmp;
		    });
		    $result[] = $tmp;
		    unset($tmp);
	    });

	    dd($result);

	    return $result;
    }

    protected function getCompanyByServices($services, $service_date, $postcode)
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

    public function testMethod2()
    {
//       $needed = $services;
        $needed = [13, 14];
        $srv = Company::with('timeslots', 'services')->get();

        $srv = $srv->filter(function ($v, $k) use ($needed) {
            $aCompany = $v->toArray();
            if (empty($aCompany['services'])) {
                return false;
            };

            $aService = [];

            foreach ($aCompany['services'] as $service) {

                if (isset($service['id'])) {
                    $aService[] = $service['id'];
                }
            }

            if (count(array_intersect($needed, $aService)) == count($needed)) {
                return true;
            }

            return false;
        });

//        dd($srv->toArray());

        $result = [];
        $srv->each(function ($firm) use (&$result) {
            $tmp = [
                'id' => $firm->id,
                'name' => $firm->name,
                'logo' => $firm->logo
            ];

            $firm->services->each(function ($service, $k) use (&$tmp, $firm) {


                if ($service->parent_id) {
                    return false;
                }

                $srv_tmp = [
                    'id' => $service->id,
                    'price' => $service->pivot->price,
                    'name' => $service->name,
                ];
                $subs = Company::find($firm->id)->services()->whereIn('services.id', $service->subs->pluck('id'))->get();
//                dd($firm->id);
//                dd(Company::find($firm->id)->services()->get()->toArray());
//                dd($subs);
                $additional = [];


                $subs->each(function ($sub) use (&$additional) {
                    $additional[] = [
                        'id' => $sub->id,
                        'name' => $sub->name,
                        'price' => $sub->pivot->price
                    ];
                });
//                dd($additional);
                $srv_tmp['additional'] = $additional;

                $tmp['services'][$service->id] = $srv_tmp;
            });
            $result[] = $tmp;
            unset($tmp);
        });

        return $result;
    }

	/**
	 * Продължаване на боокинг
	 *
	 * @param $id
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
	 */
    public function restore($id)
    {

//	    $restore_data = TempData::findByUuid($id)->getData();

	    $restore_data = TempData::where('uuid', '=', $id)->where('company_id', 0)->get();

	    if ($restore_data->isEmpty()) {
		    return redirect('/');
	    }

	    $restore_data = $restore_data->first()->getData();

        return view('test.index', ['restore_data' => $restore_data]);
    }
}
