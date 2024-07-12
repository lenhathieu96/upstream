<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Province;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('province.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $country = Country::all();
        return view('province.create',['country'=>$country]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $province = new Province();
        $data = [
            'country_id' =>$request->country_id,
            'province_name' =>$request->province_name,
            'province_code' =>$request->province_code,
            'status'=>$request->status 
        ];
        $province->create($data);
        return redirect()->route("province.index")->with('success','Province created successfull');
    }

    /**
     * Display the specified resource.
     */
    public function show(Province $province)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Province $province)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Province $province)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Province $province)
    {
        //
    }

    public function filter_by_country($id)
    {
        $country = Country::find($id);
        $province = $country->province()->get();
        return $province;
    }

    public function dtajax(Request $request)
    {
            $province = Province::all()->sortDesc();
            $out =  DataTables::of($province)->make(true);
            $data = $out->getData();
            for($i=0; $i < count($data->data); $i++) {
                $data->data[$i]->country_name = Country::find($data->data[$i]->country_id)->country_name;
            }
            $out->setData($data);
            return $out;
    }
}
