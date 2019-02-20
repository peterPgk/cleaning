<?php

namespace App\Http\Controllers;

use App\Company;
use App\Holidays;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Http\Requests;

class CompanyScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $main = Company::getMainAccount();

	    /**
	     * company selected workdays
	     */
	    $workdays = $main->getCompanyWorkdays(true);
	    /**
	     * all available holidays
	     */
	    $holidays = Holidays::all();

	    /**
	     * company selected holidays
	     */
	    $restdays = $main->getCompanyRestdays();

        return view('admin.schedule.index', [
        	'weekdays' => $workdays,
	        'max_jobs' => (int)$main->max_jobs,
	        'jobs_limit' => $main->getSubscriptionLimit(),
	        'holidays' => $holidays,
	        'restdays' => $restdays
        ]);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $company = Company::getMainAccount();
        $aTimeslots = explode('|',$data['timeslots']);
        $from = Carbon::parse($aTimeslots[0])->toDateTimeString();
        $to= Carbon::parse($aTimeslots[1])->toDateTimeString();

        $company->timeslots()->create([
//            'from' => Carbon::parse($data['date']['start'])->toDateTimeString(),
            'from' => $from,
//            'to' => Carbon::parse($data['date']['end'])->toDateTimeString(),
            'to' => $to,
            'available' => (int) $data['available'],
        ]);

        $timeSlots = $company->timeslots()->get();

        return $timeSlots;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update all Schedule data at once.
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {

	    Company::getMainAccount()->workdays()->update(['workdays' => json_encode($request->get('workdays'))]);
	    Company::getMainAccount()->restdays()->sync($request->get('holidays'));
	    Company::getMainAccount()->update(['max_jobs'=>$request->get('max_jobs')]);

	    return json_encode(['status' => 'ok']);

//	    Company::getMainAccount()->workdays()->update(['workdays' => json_encode($request->get('workdays'))]);
//
//	    return json_encode(['status' => 'ok']);
    }

	/**
	 * @param Request $request
	 *
	 * @return string
	 */
//	public function updateMaxJobs( Request $request ) {
//
//		Company::getMainAccount()->update($request->all());
//
//		return json_encode(['status' => 'ok']);
//    }

	/**
	 * @param Request $request
	 *
	 * @return string
	 */
//	public function updateHolidays(Request $request) {
//
//		Company::getMainAccount()->restdays()->sync($request->get('holidays'));
//
//		return json_encode(['status' => 'ok']);
//    }
//
        /**
     * Update all Schedule data at once.
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
//    public function updateAllData(Request $request)
//    {
//	    Company::getMainAccount()->workdays()->update(['workdays' => json_encode($request->get('workdays'))]);
//            Company::getMainAccount()->restdays()->sync($request->get('holidays'));
//            Company::getMainAccount()->update(['max_jobs'=>$request->get('max_jobs')]);
//
//	    return json_encode(['status' => 'ok']);
//    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $company = Company::getMainAccount();
        $company->timeslots()->where('id',$id)->get()->first()->delete();

        $timeSlots = $company->timeslots()->get();

        return $timeSlots;
    }
}
