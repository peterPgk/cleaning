<?php

namespace App\Http\Controllers;

use App\Company;
use App\ServiceCategories;
use Illuminate\Http\Request;

use App\Http\Requests;
use Symfony\Component\EventDispatcher\Tests\Service;

class CompanyServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function index()
	{

//		$selected = $this->getSelectedServices();
//
//		$services = [];
//		$service_cats = ServiceCategories::where('parent_id', 0)->with('childs.services')->get();
//
//		$service_cats->each(function ($categoryData) use (&$services, $selected) {
//			$sub_result = [];
//			$categoryData->childs()->each(function ($subCatData) use (&$sub_result, $selected) {
//				$sub_result[$subCatData->name] = [
//					'is_extra' => !!$subCatData->is_extra,
//					'name' => $subCatData->name,
//					'data' => []
//				];
//
//				$t = [];
//				$subCatData->services()->each(function ($serviceData) use ($subCatData, &$sub_result, $selected) {
//					$sub_result[$subCatData->name]['data'][] = [
//						'id' => (string)$serviceData->id,
//						'name' => $serviceData->name,
//						'limit' => $serviceData->limit,
//						'prices_number' => $serviceData->prices_number,
//						'sub' => $serviceData->subs()->get(),
//						'my_price' => collect($selected->where('id', $serviceData->id)->first())->only(['pivot'])->first()
//					];
//				});
//
//			});
//			$services[] = [
//				'id' => (string)$categoryData->id,
//				'text' => $categoryData->name,
//				'additional' => $sub_result
//			];
//		});

		$services = $this->loadServicesData();

		return view('admin.services.index', compact('services'));

	}

	/**
	 * @return array
	 */
	public function loadServicesData()
	{
		$result = [];
		$user = Company::getMainAccount();

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
		return $result;
	}

//    protected function getSelectedServices() {
//
//        return Company::getMainAccount()->getCompanySelectedServices();
//        $service_cats->each(function ($v, $k) use (&$result) {
//            if(is_null($v->parent)) {
//                $result[$v->categories->parent_id][$v->categories->name][$v->id] = [
//                    'id' => (string)$v->id,
//                    'price' => $v->pivot->price
//                ];
//            } elseif(!is_null($v->parent)) {
//                $result[$v->parent->categories->parent_id][$v->parent->categories->name][$v->parent_id]['sub'][$v->id] = [
//                    'id' => (string)$v->id,
//                    'price' => $v->pivot->price
//                ];
//            }
//        });
//
//        return $result;
//    }

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


        $result = \Auth::user()->subs()->get(['id', 'email', 'created_at']);
        return $result;

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
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
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $user = \Auth::user()->subs()->where('id', $id)->get()->first();

//		$user = [
//			'id'           => '1',
//			'email'        => 'user@user.bg',
//			'date_created' => '10/05/2016'
//		];


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
//		$users = [
//			[
//				'id'           => '1',
//				'email'        => 'user@user.bg',
//				'date_created' => '10/05/2016'
//			],
//			[
//				'id'           => '2',
//				'email'        => 'user2@user.bg',
//				'date_created' => '10/05/2016'
//			],
//			[
//				'id'           => '3',
//				'email'        => 'user5@user.bg',
//				'date_created' => '12/06/2016'
//			]
//		];

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
