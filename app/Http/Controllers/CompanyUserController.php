<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;

use App\Http\Requests;

class CompanyUserController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {

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
//				'email'        => 'user3@user.bg',
//				'date_created' => '12/06/2016'
//			]
//		];
        $users =\Auth::user()->subs()->get();

		return view( 'admin.users.index', compact( 'users') );
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
		$username = $request->get( 'user_email' );
		$password = $request->get( 'user_pass' );
		\Auth::user()->createSubUser( $username, $password );


        $result = \Auth::user()->subs()->get(['id','email','created_at']);
        return $result;

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

        $user =\Auth::user()->subs()->where('id',$id)->first();

		$url = ["/admin/user/{$id}/update"];

		return view( 'admin.users.edit', compact( 'user', 'url' ) );

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update( Request $request, $id ) {

        $user =\Auth::user()->subs()->where('id',$id)->first();
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
	public function destroy( $id ) {


	    $subUser =\Auth::user()->subs()->where('id',$id)->first();
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
        $result = \Auth::user()->subs()->get(['id','email','created_at']);
        return $result;

	}
}
