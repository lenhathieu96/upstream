<?php

namespace App\Http\Controllers;

use App\Models\CropInformation;
use App\Models\CropVariety;
use Illuminate\Http\Request;

use Yajra\DataTables\DataTables;

class CropVarietyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('crop_variety.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $crop_information = CropInformation::all();
        return view('crop_variety.create',['crop_information'=>$crop_information]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $crop_variety = new CropVariety();
        $data = [
            'crop_id' =>$request->crop_information_id,
            'name' =>$request->crop_variety_name
        ];
        $crop_variety->create($data);
        return redirect()->route("crop_variety.index")->with('success','District created successfull');
    }

    /**
     * Display the specified resource.
     */
    public function show(CropVariety $cropVariety)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CropVariety $cropVariety)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CropVariety $cropVariety)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CropVariety $cropVariety)
    {
        //
    }

    public function dtajax(Request $request)
    {
        $crop_variety = CropVariety::all()->sortDesc();
        $out =  DataTables::of($crop_variety)->make(true);
        $data = $out->getData();
        for($i=0; $i < count($data->data); $i++) {
            $data->data[$i]->crop_information_name = CropInformation::find($data->data[$i]->crop_id)->name;
        }
        $out->setData($data);
        return $out;
    }
}
