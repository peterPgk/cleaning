<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/test', 'TestController@index');
Route::get('/test2', 'TestController@testMethod');
Route::get('/test3', 'TestController@testMethod2');
Route::post('/customers/step1', 'TestController@getSubcategories');
Route::post('/customers/step2', 'TestController@stepTwo');
Route::post('/customers/step3', 'TestController@stepThree');
Route::post('/customers/step4', 'TestController@stepFour');
Route::post('/customers/step5', 'TestController@stepFive');
Route::post('/customers/step6', 'TestController@stepSix');
Route::post('/customers/step7', 'TestController@stepSeven');
Route::get('/customers/restore/{id}', 'TestController@restore');

Route::get('temp', 'TestController@getCompanyTemp');

Route::get('invoices', function(){
    $user = \App\Company::getMainAccount();
    $invoices = $user->invoices()->last();
    dd($invoices);
    return view('admin.invoices')->with(compact('invoices'));

});


Route::get('email/test', function () {
    $invoices = [];
    $companies = \App\Company::getAllMainActive();
    $companies->each(function(\App\Company $company) use (&$invoices){
        try{
            $invoices[$company->name] = $company->invoices()->last()->id;
        } catch (Exception $e) {

        }

    });

    dd($invoices);
});

Route::get('email/customerFeedback', function () {
    $orders = \App\TempData::getOrders();
    $orders->each(function (\App\TempData $order) {
        $order_data = $order->getData();
        $company = $order->company()->first();
        try {
            \Mail::to($order_data->email)->send(new \App\Mail\CustomerFeedback($company, $order->uuid));
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    });
});

Route::get('email/quoteReserved', function () {
    $unfinished = \App\TempData::getUnfinshedOrders();
//    dd($unfinished);

    $unfinished->each(function (\App\TempData $order) {
        $customer = $order->getData();
        $uuid = $order->uuid;
        if(isset($customer->firstname)) {
            $name = $customer->firstname . ' ' . $customer->lastname;
            try {
                \Mail::to($customer->email)->send(new \App\Mail\CustomerQuoteReserved($uuid, $name));
            } catch (Exception $e) {
                dd($e->getMessage());
            }
        }
//        dd($name);

    });
});

/**
 * User rating page
 */
Route::get('/rating/{uuid}/', 'RatingController@index');
Route::post('/rating/store', 'RatingController@store');

Route::get('/', 'TestController@index');

Route::group(['middleware' => 'auth'], function () {

    /**
     * Master admin
     */
    Route::group(['prefix' => 'master'], function () {

        Route::get('/', 'MasterAdminController@index');
        Route::post('/', 'MasterAdminController@index');

        Route::get('/settings', 'MasterAdminController@settings');
        Route::post('/settings/edit', 'MasterAdminController@store');
        Route::get('/companies', 'MasterAdminController@companies');
        Route::put('/companies/{company}/update', 'MasterAdminController@approve');

        Route::get('/company/{company}/general', 'MasterAdminController@companyGeneral');
        Route::post('/company/{company}/update', 'MasterAdminController@companyGeneralUpdate');
        Route::post('/company/{company}/general/logo', 'MasterAdminController@companyLogoUpdate');

        Route::get('/company/{company}/additional', 'MasterAdminController@companyAdditionalUpdate');
        Route::post('/company/{company}/additional/liability', 'MasterAdminController@companyLiabilityImage');
        Route::post('/company/{company}/update-info', 'MasterAdminController@updateCompanyData');

        Route::get('/company/{company}/prices', 'MasterAdminController@CompanyPrices');
        Route::post('/company/{company}/update-prices', 'CollectInfoController@saveServices');

        Route::get('/companies/activity', 'MasterAdminController@companyActivity');

        //* kris begin */
        Route::get('/total', 'MasterAdminController@total');
        Route::post('/total', 'MasterAdminController@total');

//		Route::get('/revenue_vat', 'MasterAdminController@revenue_vat');
//		Route::get('/revenue_withoutvat', 'MasterAdminController@revenue_withoutvat');

        Route::get('/topcompanies', 'MasterAdminController@topcompanies');
        Route::post('/topcompanies', 'MasterAdminController@topcompanies');

        Route::get('/ttt', 'MasterAdminController@newsubsribers');

        Route::get('/successfulbookings', 'MasterAdminController@successfulbookings');
        //* kris end */
    });


    /**
     * Company admin
     */
    Route::group(['prefix' => 'admin'], function () {
        /**
         * Admin Company data edit
         */
        Route::get('/', 'AdminController@index');
        Route::post('/', 'AdminController@index');
        Route::get('/help', 'AdminController@help');
        Route::get('/company/general', 'AdminController@companyGeneral');
        //change logo
        Route::post('/company/general/logo', 'AdminController@logo');
        Route::get('/company/additional', 'AdminController@companyAdditional');
        Route::post('/company/additional/liability', 'AdminController@liabilityImage');
        Route::get('/company/password', 'AdminController@companyPassword');
//      Route::get('/company/rating', 'AdminController@companyRating');
//      Route::get('/companies', 'CompanyController@index');                                //?????
        Route::post('/company/update', 'AdminController@update');
        Route::post('/company/update-password', 'AdminController@updatePassword');
        Route::post('/company/update-info', 'AdminController@updateCompanyData');

        /**
         * Admin users
         */
        Route::get('/user', 'CompanyUserController@index');
        Route::post('/user/new', 'CompanyUserController@store');
        Route::get('/user/{id}/edit', 'CompanyUserController@edit');
        Route::post('/user/{id}/update', 'CompanyUserController@update');
        Route::delete('/user/{id}/delete', 'CompanyUserController@destroy');

        /**
         * Admin Postcodes
         */
        Route::get('/postcodes', 'AdminController@showPostcode');
        Route::post('/postcodes/new', 'AdminController@addPostcode');

        /**
         * Admin rating
         */
        Route::get('company/rating', 'CompanyRatingController@index');
        Route::post('company/rating/update', 'CompanyRatingController@update');

        /**
         * Admin Availability
         * Old: must choose day and timeslot
         * New: select weekdays, and number
         */
        Route::get('/schedule', 'CompanyScheduleController@index');
        Route::post('/schedule/update', 'CompanyScheduleController@update');
//		Route::post('/schedule/update/max-jobs', 'CompanyScheduleController@updateMaxJobs');
//		Route::post('/schedule/update/holidays', 'CompanyScheduleController@updateHolidays');
//      Route::post('/schedule/new', 'CompanyScheduleController@store');
//      Route::delete('/schedule/{id}/delete', 'CompanyScheduleController@destroy');

        /**
         * Admin Services
         */
        Route::get('/services', 'CompanyServicesController@index');
        Route::post('/services/update', 'CollectInfoController@saveServices');
        Route::get('/services2', 'CompanyServicesController@test');                   //??????

        /**
         * Admin orders
         */
        Route::get('/orders', 'CompanyOrderController@index');
        Route::get('/orders/{order_data}', 'CompanyOrderController@view');
        Route::post('/orders/{id}/resendEmail', 'CompanyOrderController@resendEmail');

        /**
         * Admin subscriptions
         */
        Route::get('/subscription', 'AdminController@subscriptions');
        Route::post('/subscription/change', 'AdminController@changeSubscription');
        Route::post('/subscription/unsubscribe', 'AdminController@unsubscribe');

        Route::get('user/invoice/{invoice}', function (Request $request, $invoiceId) {
//    dd($request->all());
            $user = \App\Company::getMainAccount();
            return $user->downloadInvoice($invoiceId, [
                'vendor'  => env('COMPANY_NAME'),
                'product' => 'Monthly invoice',
            ]);
        })->name('invoice_route');

    });

    /**
     * First login
     */
    Route::group(['prefix' => 'collect'], function () {

	    Route::get('/', 'CollectInfoController@index');
	    /* Get data */
	    Route::post('/step1', 'CollectInfoController@stepOne');
	    /* Available days */
	    Route::post('/step2', 'CollectInfoController@stepTwo');
	    /* Empty step */
	    Route::post('/step3', 'CollectInfoController@stepZero');
//    Route::post('/step3', 'CollectInfoController@stepThree');
	    /* Services */
	    Route::post('/services', 'CollectInfoController@saveServices');
	    Route::post('/step4', 'CollectInfoController@stepFour');

	    Route::post('/days-off', 'CollectInfoController@daysOff');

//    Route::post('/get-collect-data', 'CollectInfoController@getData');
//    Route::get('/get-services-data', 'CollectInfoController@loadServicesData');
	    Route::get('/get-postcodes', 'AdminController@loadPostcodes');
    });

});

Route::group(['prefix' => 'company'], function () {
    /* Landing page - doing nothing */
    Route::get('signup', 'CompanySignUpController@landing');
    /* Register index page */
    Route::get('signup/index', 'CompanySignUpController@index');
    Route::post('signup/step1', 'CompanySignUpController@stepOne');
    Route::post('signup/step2', 'CompanySignUpController@stepTwo');
    Route::post('signup/step2/logo', 'CompanySignUpController@logo');
    Route::post('signup/step3', 'CompanySignUpController@stepThree');
    Route::post('signup/step3/liability-image', 'CompanySignUpController@liabilityImage');
    Route::post('signup/step4', 'CompanySignUpController@stepFour');
//    Route::post('signup/step5', 'CompanySignUpController@stepFive');
//    Route::post('signup/step6', 'CompanySignUpController@stepSix');

    Route::get('signup/get-plans', 'CompanySignUpController@getSubPlans');
    Route::get('signup/step5/get-service-categories', 'CompanySignUpController@getServiceCategories');

    Route::get('{id}', 'CompanyController@show');


});

