<?php

namespace App\Http\Controllers;

use App\Company;
use App\Service;
use App\ServiceCategories;
use App\TempData;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;

class CompanyOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company_orders = Company::getMainAccount()->orders()->get();
        $result = [];

        foreach ($company_orders as $order) {
            $order_data = $order->getData();

//            debug($order_data);

            list($from, $to) = explode('|', $order_data->timeslots);

            $result[] = [
                'id' => $order->id,
                'from' => $order_data->firstname . ' ' . $order_data->lastname,
                'email' => $order_data->email,
                'service_date' => Carbon::parse($order_data->service_date)->format('d.m.Y'),
                'service_time' => Carbon::parse($from)->format('H:00') . ' - ' . Carbon::parse($to)->format('H:00'),
                'services' => $order_data->company->services,

            ];
        }

        $orders = $result;

//        $users =\Auth::user()->subs()->get();

        return view('admin.orders.index', compact('orders'));
    }

	/**
	 * Display a specific order page
	 *
	 * @param TempData $order_data
	 *
	 * @return \Illuminate\Http\Response
	 */
    public function view(TempData $order_data) {

    	$order = json_decode($order_data->data, true);

	    $order['category_name'] = ServiceCategories::where('id', $order['services'])->pluck('name')->first();

	    /**
	     * KRIS
	     */
        foreach($order['company']['services'] as $key => $service){
            $serv = Service::where('id', $service['id'])->first();

            $order['company']['services'][$key]['category'] =  $serv->categories()->pluck('name')->first();
        }

	    $url = "/admin/orders/{$order_data->id}/resendEmail";

        return view('admin.orders.view', compact('order', 'url'));
    }

    /**
     * Display a specific order page
     *
     * @return \Illuminate\Http\Response
     */
    public function resendEmail($id) {
        $order = Company::getMainAccount()->orders()->where('id', $id)->get()->first();
        $order_data = $order->getData();
        $booking_fee = MasterSettigns::where('setting_name', 'booking')->get()->first();

        if(is_array($order_data->company->region)){
            $region_price = $order_data->company->region[0]->price;
        } else {
            $region_price = $order_data->company->region->price;
        }

        $total_sum = ((int)$order_data->company->price + (int)$region_price) * 100; //in cents
        $reservation_fee = $total_sum * $booking_fee['value'] / 100; //booking fee

        \Mail::to($order_data->email)->send(new ClientBookingEmail($order_data, $total_sum, $reservation_fee));
        //\Mail::to($company->email)->send(new CompanyBookingEmail($order_data, $total_sum, $sum));
        return ['result' => 'ok'];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $username = $request->get('user_email');
        $password = $request->get('user_pass');
        \Auth::user()->createSubUser($username, $password);

        return \Auth::user()->subs()->get(['id', 'email', 'created_at']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = \Auth::user()->subs()->where('id', $id)->get()->first();

        $url = ["/admin/user/{$id}/update"];

        return view('admin.users.edit', compact('user', 'url'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = \Auth::user()->subs()->where('id', $id)->get()->first();
        $user->password = $request->get('user_pass');
        $user->save();

        return redirect('/admin/user');
//		return view( 'admin.users.index', compact( 'users') );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


        $subUser = \Auth::user()->subs()->where('id', $id)->get()->first();
        $subUser->delete();
//		$users = [ [
//				'id'           => '1',
//				'email'        => 'user@user.bg',
//				'date_created' => '10/05/2016'
//			],
//			[
//				'id'           => '3',
//				'email'        => 'user3@user.bg',
//				'date_created' => '12/06/2016'
//			]
//		];
        $result = \Auth::user()->subs()->get(['id', 'email', 'created_at']);
        return $result;

    }
}
