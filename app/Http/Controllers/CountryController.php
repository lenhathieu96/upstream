<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('country.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('country.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $country = new Country();
        $data = [
            'country_name' =>$request->country_name,
            'country_code' =>$request->country_code,
            'status'=>$request->status 
        ];
        $country->create($data);
        return redirect()->route("country.index")->with('success','Country created successfull');
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Country $country)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Country $country)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        //
    }

    public function dtajax(Request $request)
    {
            $country = Country::all()->sortDesc();
            $out =  DataTables::of($country)->make(true);
            $data = $out->getData();
            for($i=0; $i < count($data->data); $i++) {
             }
            $out->setData($data);
            return $out;
    }
}
