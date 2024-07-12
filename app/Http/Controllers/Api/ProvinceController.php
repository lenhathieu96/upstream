<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $province = Province::cursor();
        return $this->success($province, 'Get all province successfully');
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
        $province = new Province();
        $data = [
            'country_id' =>$request->country_id,
            'province_name' =>$request->province_name,
            'province_code' =>$request->province_code,
            'status'=>$request->status 
        ];
        $province->create($data);
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
        if(isset($country))
        {
            $province = $country->province()->get();
            return response()->json([
                'result' => true,
                'message' => 'Get all province success fully',
                'data' =>$province
            ]);    
        }
        return response()->json([
            'result' => false,
            'message' => 'Data not found'
        ]);
    }
}
