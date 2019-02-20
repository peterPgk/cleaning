<?php

namespace App\Http\Controllers;

use App\Association;
use App\Company;
use App\CompanyData;
use App\Http\Requests\CompanySignUpStepOne;
use App\Http\Requests\CompanySignUpStepTwo;
use App\Http\Requests\CompanySignUpStepThree;
use App\Mail\CompanySubscribedEmail;
use App\Mail\GettingStarted;
use App\Mail\NewSubscriptionAdmin;
use App\Mail\NewSubscriptionEmail;
use App\MasterSettigns;
use App\Service;
use App\ServiceCategories;
use App\SubscriptionElement;
use App\SubscriptionPlan;
use App\TempData;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class CompanySignUpController extends Controller
{

	public function landing()
	{
		$data = ['title' => 'Firm register'];
		return view('auth.steps.landing', compact('data'));
	}

    public function index()
    {
        $data = ['title' => 'Firm register'];
        return view('auth.register', compact('data'));
    }

    /**
     * STEP 1 - PRICING TABLE
     *
     * @param CompanySignUpStepOne $request
     *
     * @return mixed
     */
    public function stepOne(CompanySignUpStepOne $request)
    {
        $id = TempData::add($request->all());
        return json_encode(['share' => ['uuid' => $id->string]]);
    }

	/**
	 * STEP 2 - REGISTRATION INFO
	 *
	 * @param CompanySignUpStepTwo|Request $request
	 *
	 * @return string
	 */
    public function stepTwo(CompanySignUpStepTwo $request)
    {
//        dd($request->all());
        $cData = TempData::findByUuid($request->get('uuid'));

        if(empty($cData->getData()->logo)) {
            return new JsonResponse([['name' => 'logo', 'message' => 'Logo is required!']], 422);
        }
//        dd($cData->getData());
//        $data = array_merge((array)$cData->getData(),(array)$request->all());
        $cData->updateData($request->all())->save();

        return json_encode(['status' => 'ok']);
    }

    /**
     * STEP 3 - ADDITIONAL INFO
     *
     * @param Request $request
     *
     * @return string
     */
    public function stepThree(CompanySignUpStepThree $request)
    {
//	    dd($request->all());
        $cData = TempData::findByUuid($request->get('uuid'));
        $data = $request->all();

        //Check for liability params
	    if( $data['liability'] ) {

//		    if ( empty($cData->getData()->liability_certificate) ) {
//			    return new JsonResponse([['name' => 'liability_certificate', 'message' => 'Liability certificate is required!']], 422);
//		    }

            $data['liability_expires'] = Carbon::parse($data['liability_expires'])->format('Y-m-d');
	    }

	    /**
	     * това вече е стамо стринг - година. За сега не се валидира
	     */
//	    $data['date_established'] = Carbon::parse($data['date_established'])->format('Y');
	    $data['members_of'] = json_encode($data['members_of']);

	    $cData->updateData($data)->save();
        return json_encode(['status' => 'ok']);
    }

    /**
     * STEP 4 - STRIPE DETAILS
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function stepFour(Request $request)
    {
        //        return json_encode(['test']);
        $cData = TempData::findByUuid($request->get('uuid'));
        $cData = $cData->getData();
        $cData->liability_expires = Carbon::parse($cData->liability_expires)->toDateTimeString();
//        \Storage::copy(storage_path('app/temp_images/' . $cData->logo),
//            public_path('/img/logos/' . $cData->logo));

        $tmp_password = str_random(10);

        $company = Company::where('email', $cData->email)->get()->first();

        if (!$company) {
            $company = Company::create([
                'email' => $cData->email,
                'name' => $cData->name,
                'password' => $tmp_password,
                'logo' => $cData->logo
            ]);
        }

        try {

            
            
            $company = Company::where('email', $cData->email)->get()->first();
            $plan = SubscriptionPlan::where('stripe_plan',$request->get('plan'))->get()->first();
            //\Mail::to($company->email)->send(new CompanySubscribedEmail($tmp_password));
            \Mail::to($company->email)->send(new GettingStarted($company,$plan,$tmp_password));
            //\Mail::to(config('mail.admin_email'))->send(new NewSubscriptionAdmin($company));
            \Mail::to('kriszdravkov@gmail.com')->send(new NewSubscriptionEmail($cData->name,$plan->name));
            
            
            

            CompanyData::create(array_merge((array)$cData, ['company_id' => $company->id]));
            $company->serviceTypes()->sync($cData->services);

            $company->newSubscription($request->get('plan'), $request->get('plan'))->create($request->get('id'), [
                'email' => $company->email
            ]);


        } catch (\Exception $e) {
            $company->delete();
            if ($request->expectsJson()) {
                return response()->json([
                    "code" => "general_error",
                    "message" => $e->getMessage(),
                    "param" => null,
                    "type" => "general_error",
                ], 403);
            }
        }




//        \Mail::send("Your password is: {$tmp_password}", function ($m) use ($company) {
//            $m->from('adminemail@clean.anchev.eu', 'Admin Accout');
//
//            $m->to($company->email, $company->name)->subject('Your new account as cleaning company');
//        });

        return new JsonResponse(['status' => 'ok']);
    }

	/**
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
    public function getSubPlans()
    {

        return SubscriptionElement::with('plans')->get();
    }

	/**
	 * Филтрираме плановете за да се покажат само по-големи от настоящия на
	 * клиента
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getBiggerPlans() {
		$plan = Company::getMainAccount()->getCompanySubscriptionPlan();


		return SubscriptionElement::with(
			['plans' => function ($query) use ($plan) {

				$query->where( 'price', '>=', $plan->price);
			}])->get();

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
        $uuid = null;

        /** @var UploadedFile $file */
        $file = null;
        collect($request->allFiles())->first(function (UploadedFile $val, $key) use (&$uuid, &$file) {
            $uuid = $key;
            $file = $val;
        });

        $cData = TempData::findByUuid($uuid);
        $file_name = 'logo_' . $uuid . '.' . $file->getClientOriginalExtension();
        try {
            if(!in_array($file->getMimeType(),['image/jpeg','image/pjpeg','image/png'])){
                throw new \Exception('Only JPG and PNG file formats are allowed!');
            }
	        $cData->updateData(['logo' => $file_name])->save();
	        $file->move(public_path() . "/img/logos/", $file_name);
        }
        catch (\Exception $e) {
	        return new JsonResponse(['error' => ['name' => 'logo', 'message' => $e->getMessage()], 'url' => '/company/signup/step2'], 422);
        }

        return new JsonResponse(['status' => 'ok']);
    }

    public function getServiceCategories()
    {
//        $services = ServiceCategories::where('parent_id', '=', 0)->where('is_extra', 0)->get();
//        $result = [];
//        foreach ($services as $service) {
//
//            $result[] = [
//                'id' => $service->id,
//                'text' => $service->name
//            ];
//        }

	    /**
	     * използва се на няколко места, затова го изпращам два пъти - директно
	     * и във firmData
	     */
	    $service_categories = ServiceCategories::getRootCategoriesSelect();

//	    $cleaning_quarantee = [
//		    ['id' => 1, 'text' => '24 hours'],
//		    ['id' => 2, 'text' => '48 hours'],
//		    ['id' => 3, 'text' => '1 week'],
//		    ['id' => 4, 'text' => '2 weeks'],
//		    ['id' => 5, 'text' => '3 weeks'],
//		    ['id' => 6, 'text' => '4 weeks'],
//		    ['id' => 7, 'text' => 'No guarantee']
//	    ];

	    $how_did_hear_about_us = [
		    ['id' => 1, 'text' => 'Search Engine (Google/Yahoo/Bing)'],
		    ['id' => 2, 'text' => 'Friends/Family/Word of mouth'],
		    ['id' => 3, 'text' => 'Email communication'],
		    ['id' => 4, 'text' => 'Facebook'],
		    ['id' => 5, 'text' => 'Other'],
	    ];

	    $firmData = [
	    	'service_categories' => $service_categories,
	        'members_of' => $this->getAssociations(),
		    'booking_fee' => MasterSettigns::where('setting_name', 'booking')->first(),
		    'cleaning_quarantee' => [
			    ['id' => 1, 'text' => '24 hours'],
			    ['id' => 2, 'text' => '48 hours'],
			    ['id' => 3, 'text' => '1 week'],
			    ['id' => 4, 'text' => '2 weeks'],
			    ['id' => 5, 'text' => '3 weeks'],
			    ['id' => 6, 'text' => '4 weeks'],
			    ['id' => 7, 'text' => 'No guarantee']
		    ],
		    'liability_amount' => [
                    [ 'id' => 1, 'text' => '1 million' ],
                    [ 'id' => 2, 'text' => '2 million' ],
                    [ 'id' => 3, 'text' => '3 million' ],
                    [ 'id' => 4, 'text' => '4 million' ],
                    [ 'id' => 5, 'text' => '5+ million']
                ]
	    ];

//        return ['store' => ['service_categories' => $result, 'cleaning_quarantee' => $cleaning_quarantee, 'how_did_hear_about_us' => $how_did_hear_about_us, 'firmData' => $firmData]];
        return ['store' => ['service_categories' => $service_categories, 'how_did_hear_about_us' => $how_did_hear_about_us, 'firmData' => $firmData]];
    }

    public function getAssociations()
    {
        return Association::all();
    }

    public function liabilityImage(Request $request)
    {
        $uuid = null;
        /** @var UploadedFile $file */
        $file = null;
        collect($request->allFiles())->first(function (UploadedFile $val, $key) use (&$uuid, &$file) {
            $uuid = $key;
            $file = $val;
        });
        $cData = TempData::findByUuid($uuid);
        $file_name = 'liability_' . $uuid . '.' . $file->getClientOriginalExtension();
        try {
            if(!in_array($file->getMimeType(),['application/pdf'])) {
                throw new \Exception('Only PDF file format is allowed!');
            }
            $cData->updateData(['liability_certificate' => $file_name])->save();
            $file->move(public_path() . "/img/liability_cert/", $file_name);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => ['name' => 'liability_certificate', 'message' => $e->getMessage()], 'url' => '/company/signup/step3'], 422);
        }

        return new JsonResponse(['status' => 'ok']);
    }
}
