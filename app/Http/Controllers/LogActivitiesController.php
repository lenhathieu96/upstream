<?php

namespace App\Http\Controllers;

use App\Models\FarmerDetails;
use App\Models\LogActivities;
use Illuminate\Http\Request;

class LogActivitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $data_log = LogActivities::all();
        $farmer_data = FarmerDetails::all();
        return view('activities.index',['data_log'=>$data_log,'farmers_data'=>$farmer_data]);
        
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
        //
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
