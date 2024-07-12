<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $district = District::all();
        return response()->json([
            'result' => true,
            'message' => 'Get all district success fully',
            'data' =>$district
        ]);
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
        $district = new District();
        $data = [
            'province_id' =>$request->province_id,
            'district_name' =>$request->district_name,
            'district_code' =>$request->district_code,
            'status'=>$request->status 
        ];
        $district->create($data);
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
        if(isset($province))
        {
            $district = $province->district()->get();
            return response()->json([
                'result' => true,
                'message' => 'Get all district success fully',
                'data' =>$district
            ]);
        }
        return response()->json([
            'result' => false,
            'message' => 'Data not found'
        ]);
    }
}
