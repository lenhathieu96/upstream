<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('district.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $province = Province::all();
        return view('district.create',['province'=>$province]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $district = new District();
        $data = [
            'province_id' =>$request->province_id,
            'district_name' =>$request->district_name,
            'district_code' =>$request->district_code,
            'status'=>$request->status 
        ];
        $district->create($data);
        return redirect()->route("district.index")->with('success','District created successfull');
    }

    /**
     * Display the specified resource.
     */
    public function show(District $district)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(District $district)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, District $district)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(District $district)
    {
        //
    }

    public function filter_by_province($id)
    {
        $province = Province::find($id);
        $district = $province->district()->get();
        return $district;
    }

    public function dtajax(Request $request)
    {
            $district = District::all()->sortDesc();
            $out =  DataTables::of($district)->make(true);
            $data = $out->getData();
            for($i=0; $i < count($data->data); $i++) {
                $data->data[$i]->province_name = Province::find($data->data[$i]->province_id)->province_name;
            }
            $out->setData($data);
            return $out;
    }
}
