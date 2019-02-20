<?php

namespace App\Http\Controllers;

use App\Company;
use App\CompanyWorkdays;
use App\Holidays;
use App\Postcode;
use App\Service;
use App\ServiceCategories;
use App\Mail\SubscriberApprovalPending;
use App\Mail\SubscriberApprovalRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CollectInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = ['title' => 'Company first login'];
        return view('admin.firstlogin', compact('data'));
    }

	/**
	 * @return string
	 */
    public function stepZero()
    {
        return json_encode(['status' => 'ok']);
    }

    /**
     * STEP 1 - GET DATA
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function stepOne(Request $request) {

	    /**
	     * Основните услуги + вече въведените цени
	     */
        $services = (new CompanyServicesController)->loadServicesData();
//        $services = $this->loadServicesData();
//        $postcodes = (new AdminController)->loadPostcodes(); //Това вече не трябва - няма цени и други глупости
        $postcodes = Postcode::all();

        $the_company = Company::getMainAccount();

	    /**
	     * Ако е започнал регистрация, но не я е завършил
	     */
        $mapData = $this->getCompanyMapData($the_company);

        $subscription = $the_company->getCompanySubscriptionPlan();

        $firmData = [
            'plan_name' => $subscription->name,
            'max_booking' => $subscription->jobs_limit,
            'info_text' => [
                '1' => [
                    'subhead' => 'Please enter your standard end of tenancy cleaning prices here. Remember:',
                    'list' => [
                        'Enter prices assuming that the properly has one bathroom',
                        'If you change VAT include the VAT in the price you enter below',
                    ]
                ],
                '2' => [
                    'subhead' => 'Please enter the price you charge for extra services below',
                    'list' => [
                        'If you change VAT include the VAT in the price you enter below',
                    ]
                ],
                '3' => [
                    'subhead' => 'Please enter your standard carpet cleaning prices here. Remember:',
                    'list' => [
                        'If you change VAT include the VAT in the price you enter below',
                        'Prices for additional services such as Leather '
                    ]
                ],
                '4' => [
                    'subhead' => 'Please enter the price you charge for extra services below',
                    'list' => [
                        'If you change VAT include the VAT in the price you enter below',
                    ]
                ]
            ]
        ];

        $holidays = Holidays::all();

        return ['store' => ['mapData' => $mapData, 'services' => $services, 'postcodes' => $postcodes, 'firmData' => $firmData, 'holidays' => $holidays]];
    }


    /**
     * AVAILABLE DAYS
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function stepTwo(Request $request)
    {
    	$company = Company::getMainAccount();

        // Add postcodes
        $company->postcodes()->sync($request->get('regions'));

        //Add workdays
        $company->workdays()->updateOrCreate(['company_id' => $company->id],['workdays' => json_encode($request->get('weekdays'))]);

        //Update max jobs
        $company->update(['max_jobs' => $request->get('jobs_number')]);

        $company->restdays()->sync($request->get('holidays'));

        return json_encode(['status' => 'ok']);

    }

    public function stepFour(Request $request)
    {

        $data = collect($request->all());

	    $services = $this->formatServices($data);

        $company = Company::getMainAccount();
        $company->services()->sync($services);

        $company->data_collected = 1;
        $company->save();

        \Mail::to($company->email)->send(new SubscriberApprovalPending($company->name));
        \Mail::to(config('mail.admin_email'))->send(new SubscriberApprovalRequest($company->name));
	    return json_encode(['status' => 'ok']);

    }

	/**
	 * @param Request $request
	 *
	 * @return string
	 */
    public function stepFive(Request $request)
    {
        /**
         *  SAVE DAYS
         */

        return json_encode(['status' => 'ok']);
    }

	/**
	 * Запазвааме услугите на всяка стъпка за да може ако се
	 * върнем да са попълнени
	 *
	 * @param Request $services
	 *
	 * @return string
	 */
	public function saveServices( Request $services ) {

		$data = collect($services->all());

		$services = $this->formatServices($data);

		$company = Company::getMainAccount();
		$company->services()->sync($services);

		return json_encode(['status' => 'ok']);
    }

	/**
	 *
	 * Попълва mapData за компанията, ако вече не е събрана информацията
	 *
	 * @param Company $company
	 *
	 * @return array
	 */
	public function getCompanyMapData(Company $company) {

		if( $company->data_collected ) {
			return [];
		}

		return [
			'regions'  => $company->getCompanyPostcodes(),
			'holidays' => $company->getCompanyRestdays(),
			'weekdays' => $company->getCompanyWorkdays(true),
			'jobs_number' => (int)$company->max_jobs
		];

    }

	/**
	 * @param Collection $data
	 *
	 * @return array
	 */
    public function formatServices ( Collection $data) {
	    //Изчистваме празните елементи
	    $filtered = $data->filter();

	    $services = [];

	    $filtered->each(function ($price, $service_id) use (&$services) {

		    if (strpos($service_id, '_') === false) {
			    $services[$service_id] = ['price' => $price];
		    } else {
			    $srv_data = explode('_', $service_id);
			    $services[$srv_data[0]]['prices'][$srv_data[1]] = $price;

		    }
	    });

	    foreach ($services as &$service) {
		    if(!empty($service['prices'])) {
			    $service['prices'] = json_encode($service['prices']);
		    }
	    }

	    return $services;
    }

	/**
	 * @return array
	 */
//    public function loadServicesData()
//    {
//        $result = [];
//        $user = Company::getMainAccount();
//
//        $selected = $user->getCompanySelectedServices();
//
//        $service_cats = $user->serviceTypes()->with('childs.services')->get();
//
//        $service_cats->each(function ($categoryData) use (&$result, $selected) {
//            $sub_result = [];
//            $categoryData->childs()->each(function ($subCatData) use (&$sub_result, $selected) {
//
//                $sub_result[$subCatData->name] = [
//                    'is_extra' => !!$subCatData->is_extra,
//                    'name' => $subCatData->name,
//                    'data' => []
//                ];
//
//                $subCatData->services()->each(function ($serviceData) use ($subCatData, &$sub_result, $selected) {
//
//                    $sub_result[$subCatData->name]['data'][] = [
//                        'id' => (string)$serviceData->id,
//                        'name' => $serviceData->name,
//                        'limit' => $serviceData->limit,
//                        'prices_number' => $serviceData->prices_number,
//	                    'extra' => $serviceData->is_additional,
////                        'sub' => $serviceData->subs()->get(),
//						'my_price' => collect($selected->where('id', $serviceData->id)->first())->only(['pivot'])->first()
//                    ];
//                });
//
//            });
//            $result[] = [
//                'id' => (string)$categoryData->id,
//                'text' => $categoryData->name,
//                'additional' => $sub_result
//            ];
//        });
//        return $result;
//    }

    /**
     * @param Request $request
     *
     * @return string
     */
//    public function daysOff(Request $request)
//    {
////		dd($request->toArray());
//
//        $temp = [
//            ['id' => '', 'day' => '2016-12-21'],
//            ['id' => '', 'day' => '2017-01-20'],
//            ['id' => '', 'day' => '2017-03-21']
//        ];
//
//        return json_encode(['store' => ['dates' => $temp]]);
//    }


//    public function getData()
//    {
//        $services = $this->loadServicesData();
//        $postcodes = (new AdminController)->loadPostcodes();
//
//        $firmData = [
//            'max_booking' => 10,
//            'logo' => ''
//        ];
//
//        return ['store' => ['services' => $services, 'postcodes' => $postcodes, 'firmData' => $firmData]];
//    }

}
