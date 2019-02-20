<?php

namespace App\Http\Controllers;

use App\Company;
use App\Rating;
use Illuminate\Http\Request;

use App\Http\Requests;

class CompanyRatingController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {

//		$rating = [
//			[
//				'id'           => '17',
//				'email'     => 'user@user.bg',
//				'rating'        => 4,
//				'date_created' => '10/05/2016',
//				'comment'   => 'First comment, Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus dolorum ea eaque et itaque odio perspiciatis. A aliquid, blanditiis cumque illo iste modi nobis numquam odio quis, quod quos saepe.',
//				'answer'    => 'First answer Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus dolorum ea eaque et itaque odio perspiciatis. A aliquid, blanditiis cumque illo iste modi nobis numquam odio quis, quod quos saepe.',
//				'completed' => true
//			],
//			[
//				'id'           => '26',
//				'email'     => 'user2@user.bg',
//				'rating'        => 3,
//				'date_created' => '15/05/2016',
//				'comment'   => 'Second comment Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus dolorum ea eaque et itaque odio perspiciatis. A aliquid, blanditiis cumque illo iste modi nobis numquam odio quis, quod quos saepe.',
//				'answer'    => '',
//				'completed' => false
//			],
//			[
//				'id'           => '31',
//				'email'     => 'user3@user.bg',
//				'rating'        => 3,
//				'date_created' => '16/05/2016',
//				'comment'   => 'Third comment Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus dolorum ea eaque et itaque odio perspiciatis. A aliquid, blanditiis cumque illo iste modi nobis numquam odio quis, quod quos saepe.',
//				'answer'    => '',
//				'completed' => false
//			],
//		];

        $company = Company::getMainAccount();
        $rating = $company->ratings()->get();
//        dd($rating->toArray());
		return view( 'admin.company.rating', compact( 'rating') );
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store( Request $request ) {

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show( $id ) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit( $id ) {

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update( Request $request ) {

//		$rating = [
//			[
//				'id'           => '17',
//				'email'     => 'user@user.bg',
//				'rating'        => 4,
//				'date_created' => '10/05/2016',
//				'comment'   => 'First comment, Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus dolorum ea eaque et itaque odio perspiciatis. A aliquid, blanditiis cumque illo iste modi nobis numquam odio quis, quod quos saepe.',
//				'answer'    => 'First answer Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus dolorum ea eaque et itaque odio perspiciatis. A aliquid, blanditiis cumque illo iste modi nobis numquam odio quis, quod quos saepe.',
//				'completed' => true
//			],
//			[
//				'id'           => '26',
//				'email'     => 'user2@user.bg',
//				'rating'        => 3,
//				'date_created' => '15/05/2016',
//				'comment'   => 'Second comment Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus dolorum ea eaque et itaque odio perspiciatis. A aliquid, blanditiis cumque illo iste modi nobis numquam odio quis, quod quos saepe.',
//				'answer'    => 'EDITED',
//				'completed' => true
//			],
//			[
//				'id'           => '31',
//				'email'     => 'user3@user.bg',
//				'rating'        => 3,
//				'date_created' => '16/05/2016',
//				'comment'   => 'Third comment Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus dolorum ea eaque et itaque odio perspiciatis. A aliquid, blanditiis cumque illo iste modi nobis numquam odio quis, quod quos saepe.',
//				'answer'    => '',
//				'completed' => false
//			],
//		];
//        dd(Rating::find($request->get('id')));
        Rating::find($request->get('id'))->update(['answer' => $request->get('answer'),'completed'=>1]);
        return Company::getMainAccount()->ratings()->get();
//        dd($request->all());
//		return json_encode($rating);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy( $id ) {

	}
}
