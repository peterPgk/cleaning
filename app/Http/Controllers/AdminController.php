<?php

namespace App\Http\Controllers;

use App\Company;
use App\Mail\SubscriptionCancel;
use App\Mail\SubscriptionUpgraded;
use App\Postcode;
use App\Service;
use App\ServiceCategories;
use Carbon\Carbon;
use Debugbar;
use File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Http\UploadedFile;

class AdminController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /**
         * Може да изпратим дата по пост
         */
        $date = request()->has('services_date') ? Carbon::createFromFormat('d/m/Y', request('services_date')) : Carbon::today();

        $company = Company::getMainAccount();
        if (!\Auth::user()->isFirstLogin()) {
            return redirect('/collect');
        }

        /**
         *
         * 25.01 - 2
         * 26.01
         * 27.01 - 3
         * 30.01
         * 04.02
         *
         */

        /**
         * All orders
         */
        $orders = $company->orders()->get();
        $data = [];

        $categories = ServiceCategories::where('parent_id', 0)->pluck('name', 'id')->toArray();

        $today_orders = $orders->filter(function (&$order) use ($categories, $date) {
            $order_data = json_decode($order->data, true);

            $order['orders'] = $order_data['company']['services'];
            $order['date'] = Carbon::parse($order_data['service_date']);
            $order['order_price'] = $order_data['company']['price'];
            $order['site_charge'] = $order_data['site_charge'] / 100;
            $order['revenue'] = $order['order_price'] - $order['site_charge'];

            $order['category'] = $categories[$order_data['services']];

            return $order['date']->isSameDay($date);
//	        return $order['date']->isToday();
        });

        $month_orders = $orders->filter(function ($order) {
            return $order['date']->between(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth());
        })->count();

        $data['today_cnt'] = $today_orders->count();
        $data['total_revenue'] = $today_orders->sum('revenue');
        $data['month_cnt'] = $month_orders;
        $data['grouped'] = $today_orders->groupBy('category')->map(function ($group) {
            return $group->count();
        });
        $data['date'] = $date->format('d.m.Y');

        /**
         * Групиране по услуга OLD
         */
        /*        $services = $today_orders->pluck('orders')->collapse()->groupBy('id');

                $services_ids = $services->keys()->toArray();
                $groups = Service::whereIn( 'id', $services_ids )->with('categories.parents')->get()->pluck('categories', 'id')->toArray();

                $services->each(function ($service) use ($groups) {
                    $service['root'] = $groups[$service->first()['id']]['parents']['name'];
                });


                $services = $services
                    ->groupBy('root');
                    ->each(function (&$group) {
                        $group['cnt'] = $group->flatten(1)->filter(function ($group) {
                            return is_array($group);
                        })->count();
                    });


                $data['grouped'] = $services->keys()->combine($services->pluck('cnt'))->all();*/

        return view('admin.index', compact('data'));
    }

    /**
     * Company change password edit page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function companyPassword()
    {

        return view('admin.company.password');
    }

    /**
     * Change company general details edit page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function companyGeneral()
    {
        $company = Company::getMainAccount();

        $mapData = $company->data()->first();

        return view('admin.company.general', compact('mapData'));
    }

    /**
     * Change company general details edit page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function help()
    {
        $company = Company::getMainAccount();


        $planData = $company->getCompanySubscriptionPlan()->toArray();

        return view('admin.help.index', compact('planData'));
    }

    /**
     * Change company additional details edit page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function companyAdditional()
    {

        $the_company = Company::getMainAccount();

        /**
         * Попълнените данни
         */
        $mapData = $the_company
            ->data()
            ->first();

        /**
         * Филтрираме данните, за да можем направо да ги подадем за запис
         */
        $mapData = collect($mapData)->only(['members_of', 'date_established', 'complaints', 'liability', 'liability_amount', 'liability_expires'])->all();

        /**
         * Какви услуги предлага
         */
        $mapData['services'] = $the_company->serviceTypes()->pluck('service_categories_id')->toArray();

        /**
         * Данните за попълване на импутите
         */
        $data = (new CompanySignUpController)->getServiceCategories()['store']['firmData'];

        return view('admin.company.additional', compact('data', 'mapData'));
    }

    /**
     * Upload logo handler
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function logo(Request $request)
    {

        /** @var UploadedFile $file */
        $file = null;
        collect($request->allFiles())->first(function (UploadedFile $val) use (&$file) {
            $file = $val;
        });

        try {
            if (!in_array($file->getMimeType(), ['image/jpeg', 'image/pjpeg', 'image/png'])) {
                throw new \Exception('Only JPG and PNG file formats are allowed!');
            }

            $company = Company::getMainAccount();

            /**
             * Try to delete old file, if exists
             */
            $old = public_path() . "/img/logos/" . $company->logo;
            File::delete($old);

            $file_name = 'logo_' . $company->id . '.' . $file->getClientOriginalExtension();

            $file->move(public_path() . "/img/logos/", $file_name);

            $company->update(['logo' => $file_name]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => ['name' => 'logo', 'message' => $e->getMessage()], 'url' => request()->getRequestUri()], 422);
        }

        return new JsonResponse(['status' => 'ok']);
    }

    /**
     * Company rating edit page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function companyRating()
    {

        return view('admin.company.rating');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function subscriptions()
    {

        $plans = (new CompanySignUpController)->getBiggerPlans();

        return view('admin.subscriptions.index', compact('plans'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function changeSubscription(Request $request)
    {

        $current_plan = Company::getMainAccount()->getCompanySubscriptionPlan()->stripe_plan;
        $upgrade_to_plan = $request->getContent();
//        dd($current_plan);

        Company::getMainAccount()->subscription($current_plan)
            ->skipTrial()
            ->swap($upgrade_to_plan);

        Company::getMainAccount()->subscriptions()->update(['name'=>$upgrade_to_plan]);

        \Mail::to(Company::getMainAccount()->email)->send(new SubscriptionUpgraded(Company::getMainAccount()));


        return new JsonResponse(['status' => 'ok']);
    }

    /**
     *
     */
    public function unsubscribe()
    {
        $current_plan = Company::getMainAccount()->getCompanySubscriptionPlan()->stripe_plan;
        $company = Company::getMainAccount()->subscription($current_plan)->cancel();

        \Mail::to(Company::getMainAccount()->email)->send(new SubscriptionCancel);


        return new JsonResponse(['status' => 'ok']);
    }


    /**
     * Update company password.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
    {
        $user = \Auth::user();
        $password = $request->get('old_pass');
        if (!empty($password) && \Auth::attempt(['email' => $user->email, 'password' => $password])) {
            $new_password = $request->get('new_pass');

            $user->password = $new_password;

        } elseif (!empty($password)) {
            return new JsonResponse([['name' => 'old_pass', 'message' => 'Wrong password!']], 403);
        }

//        $user->name = $request->get('name');
        $user->save();
        return new JsonResponse(['status' => 'ok']);
    }

    /**
     * Update company general details
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function update(Request $request)
    {

        $company = Company::getMainAccount();
        $data = $request->all();

        $company->data()->update(collect($data)->except(['email', 'logo'])->toArray());

        return new JsonResponse(['status' => 'ok']);
    }

    /**
     * Update company additional data
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updateCompanyData(Request $request)
    {
        $company = Company::getMainAccount();
        $data = $request->all();

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
        if (!$detached_services->isEmpty()) {
            $company->services()->detach($detached_services->toArray());
        }
        $company->serviceTypes()->sync($service_categories);

        unset($data['services'], $data['img']);

        $company->data()->update($data);
        return new JsonResponse(['status' => 'ok']);

    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function liabilityImage(Request $request)
    {

        /** @var UploadedFile $file */
        $file = null;
        collect($request->allFiles())->first(function (UploadedFile $val) use (&$file) {
            $file = $val;
        });
        try {
            if (!in_array($file->getMimeType(), ['application/pdf'])) {
                throw new \Exception('Only PDF file format is allowed!');
            }
            $company = Company::getMainAccount();

            $old = public_path() . "/img/liability_cert/" . $company->data->liability_certificate;
            File::delete($old);

            $file_name = 'liability_' . $company->id . '.' . $file->getClientOriginalExtension();

            $file->move(public_path() . "/img/liability_cert/", $file_name);

            $company->data()->update(['liability_certificate' => $file_name]);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => ['name' => 'liability_certificate', 'message' => $e->getMessage()], 'url' => request()->getBaseUrl()], 422);
        }

        return new JsonResponse(['status' => 'ok']);
    }

    /**
     * Вземаме кодовете
     *
     * TODO: Тука много се промениха работите, няма прайс, няма други глупости, да се оправи да не върти цикли
     *
     * @return array
     */
//	public function loadPostcodes() {
//
//		$company_id = Company::getMainAccount()->id;
//
//		/**
//		 * В предишната версия за всеки код трябваше да има различна цена,
//		 * Затова вземам и фирмите, ако вече имат някаква сума.
//		 * Сега това го няма
//		 */
//		$codes = Postcode::with(['companies' => function ($q) use ($company_id) {
//			$q->where('company_id', $company_id);
//		}])->get();
//
//		/**
//		 * Може ли да се покаже в модела как да го върне?
//		 */
//		$postcodes = [];
//
//		$codes->each(function ($code) use (&$postcodes) {
//			$postcodes[] = ['id' => $code->id, 'text' => $code->name];
//		});
//
//		return $postcodes;
//	}

    /**
     * Показваме пощенските кодовете в админа - edit page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showPostcode()
    {

        $my_codes = Company::getMainAccount()->getCompanyPostcodes();

        $all_codes = Postcode::all();

        return view('admin.postcodes.index', compact('all_codes', 'my_codes'));
    }

    /**
     * Админ - промяна на пощенските кодове - update page
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addPostcode(Request $request)
    {

        Company::getMainAccount()->postcodes()->sync($request->get('regions'));

        return json_encode(['status' => 'ok']);

    }

}
