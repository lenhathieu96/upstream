<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CropInformation;
use App\Models\FarmCatalogue;
use App\Models\FarmerDetails;
use App\Models\FarmLand;
use App\Models\FarmLandLatLng;
use App\Models\SeasonMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\FuncCall;

class FarmLandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd(FarmLandLatLng::all()->take(10));
        $farm_land_data = FarmLand::where([['season_id',3]])->with('farmer_details:id,farmer_code,full_name,farmer_photo','farm_land_lat_lng')->get();
        // dd($farm_land_data[0]);
        // foreach ($farm_land_data as $each_farm_land)
        // {
        //     $plot_data = [];
        //     $data_farmer = $each_farm_land->farmer_details;
        //     $cultivation_data = $each_farm_land->cultivation()->first();
        //     if(isset($cultivation_data))
        //     {
        //         $season_data = SeasonMaster::find($cultivation_data->season_id);
        //         $crop_information = CropInformation::find($cultivation_data->crop_id);
        //         $each_farm_land->crop_name = $crop_information->name;
        //         $each_farm_land->season_period_from = $season_data->from_period;
        //         $each_farm_land->season_period_to = $season_data->to_period;
        //         $each_farm_land->est_yeild = $cultivation_data->est_yield;
        //         $each_farm_land->harvest_date = $cultivation_data->expect_date;
        //     }
        //     else
        //     {
        //         $each_farm_land->crop_name = 'N/A';
        //         $each_farm_land->season_period_from = 'N/A';
        //         $each_farm_land->season_period_to = 'N/A';
        //         $each_farm_land->est_yeild = 'N/A';
        //         $each_farm_land->harvest_date ='N/A';
        //     }
        //     $each_farm_land->farmer_name = $data_farmer->full_name;
        //     $each_farm_land->farmer_code = $data_farmer->farmer_code;
        //     $each_farm_land->farmer_photo = uploaded_asset($data_farmer->farmer_photo);
        //     // $data_ploting = $each_farm_land->farm_land_lat_lng()->get()->toArray();
        //     // array_push($data_ploting,$data_ploting[0]);
        //     // dd($data_ploting);
        //     // foreach($data_ploting as $each_data_ploting)
        //     // {
        //     //     if($each_data_ploting->order == 1)
        //     //     {
        //     //         $each_farm_land->lat = $each_data_ploting->lat;
        //     //         $each_farm_land->lng = $each_data_ploting->lng;
        //     //     }
        //     //     $subplot = [
        //     //         'lat'=>$each_data_ploting->lat,
        //     //         'lng'=>$each_data_ploting->lng
        //     //     ];
        //     //     array_push($plot_data,$subplot);
                
        //     // }
        //     // if(count($data_ploting)>0)
        //     // {
        //     //     $subplot_final = [
        //     //         'lat'=>$data_ploting[0]->lat,
        //     //         'lng'=>$data_ploting[0]->lng
        //     //     ];
        //     //     array_push($plot_data,$subplot_final);
        //     // }
            
        //     // $each_farm_land->plot_data = $data_ploting;
        //     // dd($farm_land_data);
        // }
        $season_data = SeasonMaster::all();
        return view('farm_land.farmland_location',['farm_land_data'=>$farm_land_data,'season_data'=>$season_data]);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    public function filter_farmland(Request $request)
    {
        $farm_land_data = FarmLand::where([['season_id',$request->season_id],['status','active']])->with('farmer_details:id,farmer_code,full_name,farmer_photo','farm_land_lat_lng')->get();
        // foreach ($farm_land_data as $each_farm_land)
        // {
        //     $plot_data = [];
        //     $data_farmer = FarmerDetails::select(['full_name','farmer_code','farmer_photo'])->find($each_farm_land->farmer_id);
        //     $cultivation_data = $each_farm_land->cultivation()->first();
        //     if(isset($cultivation_data))
        //     {
        //         $season_data = SeasonMaster::find($cultivation_data->season_id);
        //         $crop_information = CropInformation::find($cultivation_data->crop_id);
        //         $each_farm_land->crop_name = $crop_information->name;
        //         $each_farm_land->season_period_from = $season_data->from_period;
        //         $each_farm_land->season_period_to = $season_data->to_period;
        //         $each_farm_land->est_yeild = $cultivation_data->est_yield;
        //         $each_farm_land->harvest_date = $cultivation_data->expect_date;
        //     }
        //     else
        //     {
        //         $each_farm_land->crop_name = 'N/A';
        //         $each_farm_land->season_period_from = 'N/A';
        //         $each_farm_land->season_period_to = 'N/A';
        //         $each_farm_land->est_yeild = 'N/A';
        //         $each_farm_land->harvest_date ='N/A';
        //     }
        //     $each_farm_land->farmer_name = $data_farmer->full_name;
        //     $each_farm_land->farmer_code = $data_farmer->farmer_code;
        //     $each_farm_land->farmer_photo = uploaded_asset($data_farmer->farmer_photo);
        //     $data_ploting = $each_farm_land->farm_land_lat_lng()->get();
        //     foreach($data_ploting as $each_data_ploting)
        //     {
        //         if($each_data_ploting->order == 1)
        //         {
        //             $each_farm_land->lat = $each_data_ploting->lat;
        //             $each_farm_land->lng = $each_data_ploting->lng;
        //         }
        //         $subplot = [
        //             'lat'=>$each_data_ploting->lat,
        //             'lng'=>$each_data_ploting->lng
        //         ];
        //         array_push($plot_data,$subplot);
                
        //     }
        //     if(count($data_ploting)>0)
        //     {
        //         $subplot_final = [
        //             'lat'=>$data_ploting[0]->lat,
        //             'lng'=>$data_ploting[0]->lng
        //         ];
        //         array_push($plot_data,$subplot_final);
        //     }
            
        //     $each_farm_land->plot_data = $plot_data;
        // }
        return $farm_land_data;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FarmLand $farmLand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data_log_activities = [];
        $data_log_activities['action'] = 'update';
        $data_log_activities['request'] = $request->all();
        $data_log_activities['lat'] = $request->staff_lat;
        $data_log_activities['lng'] = $request->staff_lng;
        $farm_land_data = FarmLand::find($id);
        $farmer_data = FarmerDetails::find($farm_land_data->farmer_id);
        $farm_photo = [];
        $land_document = [];
        if (!empty($request->all()['farm_photo'])) {
            
            foreach ($request->all()['farm_photo'] as $photo) {                        
                $id = (new UploadsController)->upload_photo($photo,$farm_land_data->id, 'farm_land');
                if (!empty($id)) {
                    array_push($farm_photo, $id);
                }
            }    
        }
      
        if (!empty($request->all()['land_document'])) {
            
            foreach ($request->all()['land_document'] as $photo) {                        
                $id = (new UploadsController)->upload_photo($photo,$farm_land_data->id, 'farm_land');

                if (!empty($id)) {
                    array_push($land_document, $id);
                }
            }    
        }
        $farm_land_data->land_document = json_decode($farm_land_data->land_document);
        if(isset($request->list_lat_lng))
        {
            $data_farm_land_lat_lng = json_decode($request->list_lat_lng);
            $farm_land_ploting = $farm_land_data->farm_land_lat_lng()->get();
            if(isset($farm_land_ploting))
            {
                foreach( $farm_land_ploting as $data_farm_land_ploting)
                {
                    $data_farm_land_ploting->delete();
                }
            }
            
        }
        else
        {
            $farm_land_ploting = [];
        }
        $data_farm_land = [
            'farmer_id' => $farm_land_data->farmer_id,
            'farm_name' => $farm_land_data->farm_name,
            'total_land_holding' => $request->total_land_holding,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'actual_area' => $request->actual_area, 
            'farm_photo' =>implode(',', $farm_photo),
            'land_ownership'=> $request->land_ownership,
            'approach_road'=> $request->approach_road, 
            'land_topology'=> $request->land_topology, 
            'land_gradient'=> $request->land_gradient, 
            'land_document'=> implode(',', $land_document), 
        ];
        
        try {
            $data_update =  $farm_land_data->update($data_farm_land);
            if($data_update)
            {
                $farm_land_lat_lng = new FarmLandLatLng;
                foreach($data_farm_land_lat_lng as $key => $lat_lng)
                {
                    $farm_land_lat_lng_data = [
                        'farmer_id'=> $farm_land_data->farmer_id,
                        'farm_land_id'=> $farm_land_data->id,
                        'order'=> $key + 1,
                        'lat'=> $lat_lng[0], 
                        'lng'=> $lat_lng[1] 
                    ];
                    $final_farm_land_lat_lng= $farm_land_lat_lng->create($farm_land_lat_lng_data);
                }
            }
            $data_log_activities['status_code'] = 200;
            $data_log_activities['status_msg'] = 'Farm Land Updated Successfully';
            $this->create_log((object) $data_log_activities);
            return response()->json([
                'result' => true,
                'message' => 'Farm Land Created Successfully',
                'data'=>[
                    'farm_land' =>$farm_land_data,
                    'farm_land_lat_lng' =>$farm_land_ploting = $farm_land_data->farm_land_lat_lng()->get(),
                ]
            ]);
        } catch (\Exception $e) {  
            $data_log_activities['status_code'] = 400;
            $data_log_activities['status_msg'] = $e->getMessage();
            $this->create_log((object) $data_log_activities);
            return response()->json([
                'result' => true,
                'message' => 'Farm Land Updated Fail',
            ]);
        }
        // return response()->json([
        //     'result' => true,
        //     'message' => 'Get Farm Land Successfully',
        //     'data' =>[
        //         'farm_land_data'=>$farm_land_data,
        //         'farm_land_ploting'=>(object) $farm_land_ploting,
        //     ]
        // ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FarmLand $farmLand)
    {
        //
    }

    public function create_log($data)
    {
        // dd($data);
        $staff = Auth::user()->staff;
        $log_actitvities = new LogActivitiesController();
        $data_log_activities = [
            'staff_id' => $staff->id,
            'type' => 359,
            'action'=>$data->action,
            'request'=>$data->request,
            'status_code'=>$data->status_code,
            'status_msg'=>$data->status_msg,
            'lat'=>$data->lat,
            'lng'=>$data->lng
        ];
        $log_actitvities->store_log((object) $data_log_activities);
        
    }
}
