<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LogActivities;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LogActivitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $data_log = LogActivities::all();
        // dd($data_log);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    //    dd(json_encode($request->all()));
    }

    public function store_log($data)
    {
        $ldate = date('Ymd');
        $current_timestamp = Carbon::now()->timestamp; 
        $log_code = $ldate.'-'.$current_timestamp;
        $log_activities = new LogActivities();
        $data_create = 
        [
            'staff_id'=>$data->staff_id,
            'type'=>$data->type,
            'code'=>$log_code,
            'request_log'=>json_encode($data->request),
            'action'=>$data->action,
            'status_code'=>$data->status_code,
            'status_msg'=>$data->status_msg,
            'lat'=>$data->lat,
            'lng'=>$data->lng
        ];
        // dd($data_create);
        $log_activities->create($data_create);
    }

    /**
     * Display the specified resource.
     */
    public function show(LogActivities $logActivities)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LogActivities $logActivities)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LogActivities $logActivities)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LogActivities $logActivities)
    {
        //
    }
}
