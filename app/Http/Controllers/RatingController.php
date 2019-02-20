<?php

namespace App\Http\Controllers;

use App\Company;
use App\Rating;
use App\TempData;
use App\Mail\SubscriberReceivedFeedback;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Http\Requests;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($uuid)
    {

        $rated = (bool)Rating::where('uuid', $uuid)->first();

        $data = ['title' => 'Firm rating', 'uuid' => $uuid, 'isRated' => $rated];
        return view('rating.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
//        dd(TempData::all());
        $data = $request->all();
        $order = TempData::findByUuid($data['uuid']);
//        dd($order);
        $order_data = $order->getData();
//        Company::find($order->company->id)->ratings()->create([
//            'email' => $order->email,
//	        'uuid' => $request->get('uuid'),
//            'rating' => $request->get('rating'),
//            'comment' => $request->get('comment')
//        ]);
//
//	    return json_encode(['ok']);


        /**
         * KRIS
         */
        $exist = Company::find($order->company_id)->ratings()->where(['email' => $order_data->email, 'uuid' => $data['uuid']])->first();

        //Can be added only one by user
        if (count($exist) == 0) {
            if ((int)$request->get('rating') > 0) {
                $add = Company::find($order->company_id)->ratings()->create([
                    'email' => $order_data->email,
                    'uuid' => $data['uuid'],
                    'rating' => $data['rating'],
                    'comment' => $data['comment'],
                    'answer' => '',
                ]);
                if ($add) {
                    $company = Company::find($order->company_id);
                    $rating = [];
                    $rating['rating'] = $data['rating'];
                    $rating['name'] = $order_data->firstname;
                    \Mail::to($order_data->email)->send(new SubscriberReceivedFeedback($company, $rating));


                    return json_encode(['status' => 'ok']);
                }
            }
        }
        return new JsonResponse(['error' => 'Error'], 422);

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
