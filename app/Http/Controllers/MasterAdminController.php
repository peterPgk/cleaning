<?php

namespace App\Http\Controllers;

use App\Company;
use App\CompanyData;
use App\MasterSettigns;
use App\ServiceCategories;
use App\TempData;
use App\Mail\SubscriberApprovalSuccess;
use Carbon\Carbon;
use File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use phpDocumentor\Reflection\Types\Object_;

class MasterAdminController extends Controller {

    public function index() {

            //if (!\Auth::user()->isFirstLogin()) {
                //return redirect('/collect');
           // }
            
         
            if(\Auth::user()->type!=1){
                      return redirect('/login');
            }
          
	    /**
	     * Total bookings by category
	     */
	    $totals = $this->getTotalBookings(request('services_date'));

	    $companies = $this->getCompanies(5);

	    $revenue = $this->getRevenue(request('services_date'));

	    $bookings_cnt = $totals['total_stats']->sum();

	    $new_subscribers = $this->getNewSubscribers(request('services_date'))->count();

        return view('master_admin.index')
	        ->with($totals)
	        ->with($companies)
	        ->with(compact('revenue', 'bookings_cnt', 'new_subscribers'));
    }

    public function settings()
    {
        if(\Auth::user()->type!=1){
                      return redirect('/login');
            }
        $db_settings = MasterSettigns::all();

        $settings = [];
        foreach ($db_settings as $db_setting) {
            $settings[$db_setting->setting_name] = [
                'id' => $db_setting->id,
                'name' => $db_setting->name,
                'value' => $db_setting->value
            ];
        }
        return view('master_admin.settings', compact('settings'));
    }

	/**
	 * Лист на всички компании, за одобрение и редакция
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function companies() {
            if(\Auth::user()->type!=1){
                      return redirect('/login');
            }
		$companies = Company::all();

		return view('master_admin.companies', compact('companies'));
    }

	/**
	 * Всички компании със услугите, групирани по категория
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function companyActivity() {
            if(\Auth::user()->type!=1){
                      return redirect('/login');
            }
		$companies = $this->getCompanies();

		return view('master_admin.companies.companies_activity')->with($companies);
    }

	/**
	 * Change company general details edit page
	 *
	 * @param Company $company
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function companyGeneral(Company $company) {
            if(\Auth::user()->type!=1){
                      return redirect('/login');
            }
		$mapData = $company->data()->first();

		return view('master_admin.companies.general', compact('mapData'));
	}


	/**
	 * Update company general details
	 *
	 *
	 * @param Company $company
	 *
	 * @return JsonResponse
	 */
	public function companyGeneralUpdate(Company $company) {
            if(\Auth::user()->type!=1){
                      return redirect('/login');
            }
		$data = request()->all();

		$company->data()->update(collect($data)->except('email', 'logo')->toArray());

		return new JsonResponse(['status' => 'ok']);
	}

        
        /**
	 * Show company prices
	 *
	 * @param Company $company
	 *
	 * 
	 */
	public function companyPrices(Company $company) {
            if(\Auth::user()->type!=1){
                      return redirect('/login');
            }
		$result = [];
                $mapData = $company->data()->first();

		$user = $company->getMainAccount();

		$selected = $user->getCompanySelectedServices();

		$service_cats = $user->serviceTypes()->with('childs.services')->get();

		$service_cats->each(function ($categoryData) use (&$result, $selected) {
			$sub_result = [];
			$categoryData->childs()->each(function ($subCatData) use (&$sub_result, $selected) {

				$sub_result[$subCatData->name] = [
					'is_extra' => !!$subCatData->is_extra,
					'name' => $subCatData->name,
					'data' => []
				];

				$subCatData->services()->each(function ($serviceData) use ($subCatData, &$sub_result, $selected) {

					$sub_result[$subCatData->name]['data'][] = [
						'id' => (string)$serviceData->id,
						'name' => $serviceData->name,
						'limit' => $serviceData->limit,
						'prices_number' => $serviceData->prices_number,
						'extra' => $serviceData->is_additional,
//                        'sub' => $serviceData->subs()->get(),
						'my_price' => collect($selected->where('id', $serviceData->id)->first())->only(['pivot'])->first()
					];
				});

			});
			$result[] = [
				'id' => (string)$categoryData->id,
				'text' => $categoryData->name,
				'additional' => $sub_result
			];
		});
                $services = $result;
		return view('master_admin.companies.prices', compact('mapData','services'));
                
	}
        
        
	/**
	 * Upload logo handler
	 *
	 * @param Company $company
	 *
	 * @return JsonResponse
	 * @internal param Request $request
	 *
	 */
	public function companyLogoUpdate(Company $company)
	{
            
		/** @var UploadedFile $file */
		$file = null;
		collect(request()->allFiles())->first(function ( UploadedFile $val ) use ( &$file ) {
			$file = $val;
		});

		try {
			if(!in_array($file->getMimeType(),['image/jpeg','image/pjpeg','image/png'])){
				throw new \Exception('Only JPG and PNG file formats are allowed!');
			}

			/**
			 * Try to delete old file, if exists
			 */
			$old = public_path() . "/img/logos/" . $company->logo;
			File::delete($old);

			$file_name = 'logo_' . $company->id . '.' . $file->getClientOriginalExtension();

			$file->move(public_path() . "/img/logos/", $file_name);

			$company->update(['logo' => $file_name]);
		}
		catch (\Exception $e) {
			return new JsonResponse(['error' => ['name' => 'logo', 'message' => $e->getMessage()], 'url' => request()->getRequestUri()], 422);
		}

		return new JsonResponse(['status' => 'ok']);
	}

	/**
	 * Change company additional details edit page
	 *
	 * @param Company $company
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function companyAdditionalUpdate(Company $company) {

		/**
		 * Попълнените данни
		 */
		$mapData = $company
			->data()
			->first();

		/**
		 * Филтрираме данните, за да можем направо да ги подадем за запис
		 */
		$mapData = collect($mapData)->only(['members_of', 'date_established', 'complaints', 'liability', 'liability_amount', 'liability_expires'])->all();

		/**
		 * Какви услуги предлага
		 */
		$mapData['services'] = $company->serviceTypes()->pluck('service_categories_id')->toArray();
		$mapData['company_id'] = $company->id;
		$mapData['name'] = $company->name;

		/**
		 * Данните за попълване на импутите
		 */
		$data = (new CompanySignUpController)->getServiceCategories()['store']['firmData'];

		return view('master_admin.companies.additional', compact('data', 'mapData'));
	}

	/**
	 * Update company additional data
	 *
	 * @param Company $company
	 *
	 * @return JsonResponse
	 * @internal param Request $request
	 *
	 */
	public function updateCompanyData(Company $company)
	{
		$data = request()->all();

		$data['members_of'] = json_encode($data['members_of']);
		$data['liability_expires'] = Carbon::parse($data['liability_expires'])->format('Y-m-d');

		/**
		 * Какви услуги предлага. Ако маха някоя категория, трбябва да изчистим услугите
		 */
		$service_categories = $data['services'];

		$old_categories = $company->serviceTypes()->with('childs.services')->get()->filter(function ($service) use ($service_categories) {
			return !in_array($service->id, $service_categories);
		});

		$detached_services = $old_categories->pluck('childs')->collapse()->pluck('services')->collapse()->pluck('id');

		/**
		 * Transaction ??
		 */

		if ( !$detached_services->isEmpty() ) {
			$company->services()->detach($detached_services->toArray());
		}
		$company->serviceTypes()->sync($service_categories);

		unset($data['services'], $data['img']);

		$company->data()->update($data);
		return new JsonResponse(['status' => 'ok']);

	}


	/**
	 * @param Company $company
	 *
	 * @return JsonResponse
	 * @internal param Request $request
	 *
	 */
	public function companyLiabilityImage(Company $company)
	{

		/** @var UploadedFile $file */
		$file = null;
		collect(request()->allFiles())->first(function (UploadedFile $val) use (&$file) {
			$file = $val;
		});
		try {
			if(!in_array($file->getMimeType(),['application/pdf'])) {
				throw new \Exception('Only PDF file format is allowed!');
			}

			$old = public_path() . "/img/liability_cert/" . $company->data->liability_certificate;
			File::delete( $old );

			$file_name = 'liability_' . $company->id . '.' . $file->getClientOriginalExtension();

			$file->move(public_path() . "/img/liability_cert/", $file_name);

			$company->data()->update(['liability_certificate' => $file_name]);

		} catch (\Exception $e) {
			return new JsonResponse(['error' => ['name' => 'liability_certificate', 'message' => $e->getMessage()], 'url' => request()->getBaseUrl() ], 422);
		}

		return new JsonResponse(['status' => 'ok']);
	}

	/**
	 * @param Company $company
	 *
	 * @return string
	 *
	 */
	public function approve( Company $company ) {

		$company->toggleApproved();
                if($company->approved){
                    \Mail::to($company->email)->send(new SubscriberApprovalSuccess($company->name));
                }
                
		$company->save();

		return json_encode(['status' => 'ok']);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store() {

        /* $settings = [
          "vat" => [
          'id' => '1',
          'name' => 'VAT',
          'value' => '20'
          ],
          "booking" => [
          'id' => '2',
          'name' => 'Booking percent',
          'value' => '5'
          ]
          ]; */

        $reqs = request()->all();
        $setting = new MasterSettigns;

        foreach ($reqs as $key => $value) {
            $setting->where('setting_name', $key)->update(['value' => $value]);
        }
        return json_encode(['status' => 'ok']);
    }


	/**
	 * Total booking by service count
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function total() {
            if(\Auth::user()->type!=1){
                      return redirect('/login');
            }
		$title = 'Total number number of bookings ';

		$totals = $this->getTotalBookings(request('services_date'));

		return view('master_admin.circlestats')->with($totals)->with(compact('title'));
	}

	/**
	 * @param null $service_date
	 *
	 * @return array
	 */
	private function getTotalBookings($service_date = null) {

		$filters = $this->getDateFilter($service_date);

		$compare = $this->mustCompare($filters);

		$categories = ServiceCategories::getRootCategoriesNames();

		$total_stats = TempData::getOrders()

	         ->filter(function (&$order) use ($categories, $compare, $filters) {
	             $order_data = $order->getData();
	             $order['category'] = $categories[$order_data->services];

	             if ( $compare ) {

	                 $order_date = Carbon::parse($order_data->service_date);

	                 return $order_date->between($filters[0], $filters[1]);
	             }

	             return true;
	         })

	         ->groupBy('category')->map(function ($group) {
				 return $group->count();
			});

		$labels = $compare ? $service_date : 'All dates';

		return compact('total_stats', 'labels');
	}

	public function topcompanies() {

		$top = $this->getCompanies(5);

		return view('master_admin.topcompanies')->with($top);
	}


	/**
	 * KRIS
	 *
	 * @param bool $only
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getCompanies($only = false) {

		$categories = ServiceCategories::getRootCategoriesNames();

		$companies = TempData::with('company')->where('company_id', '!=', 0)
			->get()

			->groupBy('company_id')

			->sortByDesc(function ($company) {
				return $company->count();
			});

		if ( is_numeric($only) ) {

			$companies = $companies->take((int)$only);
		}

		$companies->transform(function ($orders) use ($categories) {

			$c = $orders->first()->company->toArray();

			$c['bookings'] = $orders->count();

			$c['by_category'] = $orders->transform(function ($order) use ($categories) {
				return ['category' => $categories[$order->getData()->services]];
			})
				->groupBy('category')
				->transform(function ($cat) {
					return $cat->count();
				})->toArray();

			return $c;
		});

		$top_stats = $companies->pluck('by_category', 'id');

/*
		$company = new Company();

		$allCompanies = $company->where('parent_id', '=', 0)->get();
		$booking_fee = MasterSettigns::where('setting_name', 'booking')->get()->first();

		$companies = array();
		$stats = array();
		$count = [];
		$count_ssrv = [];


		$ServiceCategories = new ServiceCategories();
		$services = $ServiceCategories->mainCategories()->get();

		$servNames = [];
		foreach ($services as $serv) {
			$servNames[$serv->id] = $serv->name;
		}


		//All orders and make an array with key - company_id and value - booking count
		$company_orders = TempData::getOrders();
		foreach ($company_orders as $company_order) {
			$order_data = $company_order->getData();

			if (!isset($count[$order_data->company->id])) {
				$count[$order_data->company->id] = 1;
			} else {
				$count[$order_data->company->id] ++;
			}


			if (!isset($count_ssrv[$order_data->company->id][$order_data->services])) {
				$count_ssrv[$order_data->company->id][$order_data->services] = 1;
			} else {
				$count_ssrv[$order_data->company->id][$order_data->services] ++;
			}
		}

		// print_r($count_ssrv); die;
		$i = 0;
		foreach ($allCompanies as $company) {
			// var_dump($company); die;
			$comp_data = CompanyData::where('company_id', $company->id)->get()->first();

			$arr = [];
			$arr['id'] = $company->id;
			$arr['name'] = $comp_data['name'];
			$arr['logo'] = $company->logo;

			//use the prev array to get their revenue
			if (isset($count[$company->id])) {
				$arr['bookings'] = $count[$company->id];
			} else {
				$arr['bookings'] = 0;
			}



			if (isset($count_ssrv[$company->id])) {
				$stats[$company->id] = array();
				// print_r($count_ssrv[$company->id]);
				$k = 0;
				//  $sar[$company->id]=[];
				foreach ($count_ssrv[$company->id] as $ser_key => $r) {
					// echo $r;
					$rar[$k]['name'] = $servNames[$ser_key];
					$rar[$k]['bookings'] = $r;
					//  array('name'=>'End of tenancy','bookings'=>1),
					//   array('name'=>'Carpet cleaning','bookings'=>2),
					array_push($stats[$company->id], $rar[$k]);
					$k++;
				}
				//   print_r($rar);
			}


			// $arr['stats'] = $stats[$service->id];
			array_push($companies, $arr);
		}
		usort($companies, function($a, $b) {
			return $b['bookings'] - $a['bookings'];
		});

		$i = 0;
		foreach($companies as $comp_key => $company){
			if($i>=5){
				unset($companies[$comp_key]);
			}
			$i++;
		}


		/*  $stats = array(
		  '2' => array(
		  array('name'=>'End of tenancy','bookings'=>1),
		  array('name'=>'Carpet cleaning','bookings'=>2),
		  ),
		  '36' => array(
		  array('name'=>'End of tenancy','bookings'=>1),
		  array('name'=>'Carpet cleaning','bookings'=>2),
		  ),
		  ); */

		//die;
		//print_r($sar);
		// print_r($stats); die;

		return compact('companies', 'top_stats');
	}

	/**
	 * @param null $service_date
	 *
	 * @return float|int
	 */
	private function getRevenue ($service_date = null) {

		$filters = $this->getDateFilter($service_date);

		$compare = $this->mustCompare($filters);

		$orders = TempData::getOrders()

			->filter(function (&$order) use ($filters, $compare) {
				$order_data = $order->getData();

				$order['revenue'] = $order_data->site_charge;
//
				if ( $compare ) {

					$order_date = Carbon::parse($order_data->service_date);

					return $order_date->between($filters[0], $filters[1]);
				}

				return true;
			});

		return $orders->sum('revenue') / 100;
	}

	/**
	 * Парсваме датата за филтър
	 * @param null $service_date
	 *
	 * @return array
	 */
	private function getDateFilter ( $service_date = null ) {
		$filters = [];

		if ( !is_null($service_date) ) {

			$filters = collect(explode( '-', $service_date ))
				->map(function ($date) {
					return Carbon::createFromFormat('d/m/Y', (string)trim($date));
				})->toArray();
		}

		return $filters;
	}

	/**
	 * @param $tester
	 *
	 * @return bool
	 */
	private function mustCompare ( Array $tester = [] ) {
		return !collect($tester)->isEmpty();
	}


	/**
	 * KRIS
	 *
	 * Revenue by services with total row including VAT
	 * @return type
	 */
	/*public function revenue_vat() {
		$title = 'Total revenue from reservation fees - VAT';
		$ServiceCategories = new ServiceCategories();
		$booking_fee = MasterSettigns::where('setting_name', 'booking')->get()->first();


		$services = $ServiceCategories->mainCategories()->get();
		;
		$stats = [];
		$count = [];

		$i = 0;

		$company_orders = TempData::getOrders();
		foreach ($company_orders as $company_order) {

			$order_data = $company_order->getData();

			if (isset($order_data->company->region) && is_array($order_data->company->region)) {

				$total_sum = $order_data->company->price + $order_data->company->region[0]->price;
			} else {
				$total_sum = $order_data->company->price;
			}
			$reservation_fee = $total_sum * $booking_fee['value'] / 100; //booking fee



			if (!isset($count[$order_data->services])) {
				$count[$order_data->services] = $reservation_fee;
			} else {
				$count[$order_data->services] += $reservation_fee;
			}
		}

		foreach ($services as $service) {
			$stats['labels'][$i] = $service->name;
			if (isset($count[$service->id])) {
				$stats['values'][$i] = $count[$service->id];
			} else {
				$stats['values'][$i] = 0;
			}
			$i++;
		}

		//$stats['labels'] = array('label1','label2');
		//$stats['values'] = array('20','80');


		return view('master_admin.circlestats', compact('stats', 'title'));
	}*/


	/**
	 *
	 * KRIS
	 *
	 * Total Revenue by services with total row without VAT
	 * @return type
	 */
	/*public function revenue_withoutvat() {
		$title = 'Total revenue from reservation fees - WITHOUT VAT';
		$ServiceCategories = new ServiceCategories();
		$booking_fee = MasterSettigns::where('setting_name', 'booking')->get()->first();


		$services = $ServiceCategories->mainCategories()->get();
		;
		$stats = [];
		$count = [];

		$i = 0;

		$company_orders = TempData::getOrders();
		foreach ($company_orders as $company_order) {

			$order_data = $company_order->getData();

			if (isset($order_data->company->region) && is_array($order_data->company->region)) {

				$total_sum = $order_data->company->price + $order_data->company->region[0]->price;
			} else {
				$total_sum = $order_data->company->price;
			}
			$reservation_fee = $total_sum * $booking_fee['value'] / 100; //booking fee



			if (!isset($count[$order_data->services])) {
				$count[$order_data->services] = $reservation_fee;
			} else {
				$count[$order_data->services] += $reservation_fee;
			}
		}

		foreach ($services as $service) {
			$stats['labels'][$i] = $service->name;
			if (isset($count[$service->id])) {
				$stats['values'][$i] = $count[$service->id] - ($count[$service->id] * 20 / 100);
			} else {
				$stats['values'][$i] = 0;
			}
			$i++;
		}

		//$stats['labels'] = array('label1','label2');
		//$stats['values'] = array('20','80');


		return view('master_admin.circlestats', compact('stats', 'title'));
	}*/


	/**
	 * KRIS
	 *
	 * Total Revenue by services with total row without VAT
	 * @return type
	 */
	public function newsubsribers() {
//		$title = 'Number of new subscriptions by service';
//		$company = new Company();
//		$companies = $company->get();
//
//		$stats = [];
//		$count = [];
//
//		$i = 0;
//
//		foreach ($services as $service) {
//			$stats['labels'][$i] = $service->name;
//			if (isset($count[$service->id])) {
//				$stats['values'][$i] = $count[$service->id] - ($count[$service->id] * 20 / 100);
//			} else {
//				$stats['values'][$i] = 0;
//			}
//			$i++;
//		}

		$this->getNewSubscribers();



		//$stats['labels'] = array('label1','label2');
		//$stats['values'] = array('20','80');


		return view('master_admin.circlestats', compact('stats', 'title'));
	}


	private function getNewSubscribers($service_date = null) {

		$filters = $this->getDateFilter($service_date);

		$compare = $this->mustCompare($filters);

		if ( $compare ) {
			$companies = Company::whereBetween('created_at', $filters)->get();
		}
		else {
			$companies = Company::all();
		}

		return $companies;
	}


	/**
	 * KRIS
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function successfulbookings() { // TODO
		$title = 'Booking successful rate by services';
		$ServiceCategories = new ServiceCategories();
		$booking_fee = MasterSettigns::where('setting_name', 'booking')->get()->first();


		$services = $ServiceCategories->mainCategories()->get();

		$stats = [];
		$count = [];

		$i = 0;

		$orders = TempData::get();
		foreach ($orders as $order) {
			$order_data = $order->getData();


			if (isset($order_data->services) && is_array($order_data->services) === false) {

				if (!isset($count[$order_data->services])) {

					$count[$order_data->services]['unfinished'] = 0;
					$count[$order_data->services]['finished'] = 0;
				} else {
					if ($order->company_id > 0) {
						$count[$order_data->services]['finished'] ++;
					} else {
						$count[$order_data->services]['unfinished'] ++;
					}
				}
			}
		}


		// print_r($count); die;
		foreach ($services as $service) {
			$stats['labels'][$i] = $service->name;
			if (isset($count[$service->id])) {
				$values[$i][0] = $count[$service->id]['unfinished'];
				$values[$i][1] = $count[$service->id]['finished'];
			} else {
				$values[$i][0] = 0;
				$values[$i][1] = 0;
			}
			$i++;
		}

		foreach ($values as $value) {
			$stats['values'][] = round($value[1] / $value[0], 4);
		}

		//$stats['labels'] = array('label1','label2');
		//$stats['values'] = array('20','80');


		return view('master_admin.barstats', compact('stats', 'title'));
	}
}
