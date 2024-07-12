<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cultivations;
use App\Models\FarmCatalogue;
use App\Models\FarmerDetails;
use App\Models\FarmLand;
use App\Models\FarmLandLatLng;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FarmLandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Get Farm Lands By Farmer Id
    **/
    public function get_all_farm_land($id)
    {
        $farm_land_data = FarmLand::where('farmer_id',$id)->where('status', FarmLand::STATUS_ACTIVE)->latest()->get();
        foreach($farm_land_data as $each_farm_land_data)
        {
            if($each_farm_land_data->cultivation()->where('cultivations.status', Cultivations::STATUS_ACTIVE)->exists())
            {
                $cultivation = $each_farm_land_data->cultivation()->where('cultivations.status', Cultivations::STATUS_ACTIVE)->get();
                $each_farm_land_data->total_cultivation = $cultivation->count();
            }
            else
            {
                $each_farm_land_data->total_cultivation = 0;
            }
        }
        return response()->json([
            'result' => true,
            'message' => 'Get All Farm Land Created Successfully',
            'data'=>[
                'farm_land_data' =>$farm_land_data,
            ]
        ]);
    }

    public function get_all_farm_land_by_staff()
    {
        $farm_land = Auth::user()->staff->farm_land_count()->where('farm_lands.status', FarmLand::STATUS_ACTIVE)->get();
        foreach ($farm_land as $sub_farm_land)
        {
            $sub_farm_land->farmer_code = $sub_farm_land->farmer_details->farmer_code;
            $sub_farm_land->farmer_name = $sub_farm_land->farmer_details->farmer_name;
            $sub_farm_land->farmland_lat_lng = $sub_farm_land->farm_land_lat_lng;
        }
        // foreach($farm_land_data as $each_farm_land_data)
        // {
        //     if(isset($each_farm_land_data->cultivation))
        //     {
        //         $cultivation = $each_farm_land_data->cultivation;
        //         $each_farm_land_data->total_cultivation = $cultivation->count();
        //     }
        //     else
        //     {
        //         $each_farm_land_data->total_cultivation = 0;
        //     }
        // }
        return response()->json([
            'result' => true,
            'message' => 'Get All Farm Land Created Successfully',
            'data'=>[
                'farm_land_data' =>$farm_land,
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $staff = Auth::user();
        $data_appoarch_road = [];
        $data_land_topolog = [];
        $data_land_gradient = [];
        $data_land_document = [];
        $data_farmer = [];
        $appoarch_road = FarmCatalogue::where('NAME','Approach Road')->first();
        if(isset($appoarch_road))
        {
            $data_appoarch_road = $appoarch_road->catalogue_value()->get();
        }
        $land_topolog = FarmCatalogue::where('NAME','Land Topography')->first();
        if(isset($land_topolog))
        {
            $data_land_topolog = $land_topolog->catalogue_value()->get();
        }
        $land_gradient = FarmCatalogue::where('NAME','Land Gradient')->first();
        if(isset($land_gradient))
        {
            $data_land_gradient = $land_gradient->catalogue_value()->get();
        }
        $land_document = FarmCatalogue::where('NAME','Land Document')->first();
        if(isset($land_document))
        {
            $data_land_document = $land_document->catalogue_value()->get();
        }
        $land_owner_ship = FarmCatalogue::where('NAME','Land Ownership')->first();
        if(isset($land_owner_ship))
        {
            $data_land_owner_ship = $land_owner_ship->catalogue_value()->get();
        }
        $all_farmer = FarmerDetails::where('staff_id',$staff->staff->id)
            ->where('status', FarmerDetails::STATUS_ACTIVE)
            ->get();
        return response()->json([
            'result' => true,
            'message' => 'Farmer Created Successfully',
            'data'=>[
                'data_appoarch_road' =>$data_appoarch_road,
                'data_land_topolog' =>$data_land_topolog,
                'data_land_gradient' =>$data_land_gradient,
                'data_land_document' =>$data_land_document,
                'data_land_owner_ship'=>$data_land_owner_ship,
                'all_farmer'=>$all_farmer
            ]
            
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->farmer == 0) {
            return $this->fail('Please Select Farmer');
        }
        $data_log_activities = [];
        $data_log_activities['action'] = 'create';
        $data_log_activities['request'] = $request->all();
        $data_log_activities['lat'] = $request->staff_lat;
        $data_log_activities['lng'] = $request->staff_lng;
        $data_farm_land_lat_lng = json_decode($request->list_lat_lng);
        // dd($user->farmer_detail()->first()->id);
        $farmer_data = FarmerDetails::find($request->farmer);
        

        $data_farm_land = [
            'farmer_id' => $request->farmer,
            'farm_name' => $request->farm_name,
            'total_land_holding' => $request->total_land_holding,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'farm_land_ploting' => $request->farm_land_ploting,
            'actual_area' => $request->actual_area, 
            //'farm_photo' =>implode(',', $farm_photo),
            'land_ownership'=> $request->land_ownership, 
            'srp_score'=> $request->srp_score, 
            'carbon_index'=> $request->carbon_index, 
            'approach_road'=> $request->approach_road, 
            'land_topology'=> $request->land_topology, 
            'land_gradient'=> $request->land_gradient, 
            //'land_document'=> implode(',', $land_document), 
        ];
        $farm_land = new FarmLand;

        try {
            $final_farm_land = $farm_land->create($data_farm_land);
            if($final_farm_land)
            {
                // Process image
                $farm_photo = [];
                $land_document = [];
                if (!empty($request->all()['farm_photo'])) {
                    
                    foreach ($request->all()['farm_photo'] as $photo) {                        
                        $id = (new UploadsController)->upload_photo($photo,$final_farm_land->id, 'farm_land');

                        if (!empty($id)) {
                            array_push($farm_photo, $id);
                        }
                    }    
                }
            
                if (!empty($request->all()['land_document'])) {
                    
                    foreach ($request->all()['land_document'] as $photo) {                        
                        $id = (new UploadsController)->upload_photo($photo,$final_farm_land->id, 'farm_land');

                        if (!empty($id)) {
                            array_push($land_document, $id);
                        }
                    }    
                }

                $final_farm_land->farm_photo = implode(',', $farm_photo);
                $final_farm_land->land_document = implode(',', $land_document);
                $final_farm_land->save();

                $farm_land_lat_lng = new FarmLandLatLng;
                foreach($data_farm_land_lat_lng ?? [] as $key => $lat_lng)
                {
                    $farm_land_lat_lng_data = [
                        'farmer_id'=> $request->farmer,
                        'farm_land_id'=> $final_farm_land->id,
                        'order'=> $key + 1,
                        'lat'=> $lat_lng[0], 
                        'lng'=> $lat_lng[1] 
                    ];
                    $final_farm_land_lat_lng=$farm_land_lat_lng->create($farm_land_lat_lng_data);
                }
            }
            $data_log_activities['status_code'] = 200;
            $data_log_activities['status_msg'] = 'Farm Land Created Successfully';
            $this->create_log((object) $data_log_activities);
            return response()->json([
                'result' => true,
                'message' => 'Farm Land Created Successfully',
                'data'=>[
                    'farm_land' =>$final_farm_land,
                    'farm_land_lat_lng' =>$final_farm_land_lat_lng ?? []
                ]
            ]);
        } catch (\Exception $e) {  
            $data_log_activities['status_code'] = 400;
            $data_log_activities['status_msg'] = $e->getMessage();
            $this->create_log((object) $data_log_activities);
            return response()->json([
                'result' => true,
                'message' => 'Farm Land Failed',
            ]);
        }



        

        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $farm_land_data = FarmLand::with([
            'cultivation' => function($query) {
                $query->where('cultivations.status', Cultivations::STATUS_ACTIVE);
            },
            'cultivation.season',
            'cultivation.crops_master',
            ])->find($id);
        // dd($farm_land_data);
        $farmer_name = FarmerDetails::find($farm_land_data->farmer_id)->full_name;
        $farm_land_data->farmer_name = $farmer_name;
        $farm_land_data->actual_area = $farm_land_data->actual_area;
        $farm_land_data->farm_photo = uploaded_asset($farm_land_data->farm_photo);
        return response()->json([
            'result' => true,
            'message' => 'Get Farm Land Successfully',
            'data' =>[
                'farm_land_data'=>$farm_land_data,
                'farm_land_ploting'=>$farm_land_data->farm_land_lat_lng()->get()
            ]
        ]);
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
            'farm_name' => $request->farm_name ?? $farm_land_data->farm_name,
            'total_land_holding' => $request->total_land_holding ?? $farm_land_data->total_land_holding,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'actual_area' => $request->actual_area, 
            'land_ownership'=> $request->land_ownership ?? $farm_land_data->land_ownership,
            'approach_road'=> $request->approach_road ?? $farm_land_data->approach_road, 
            'land_topology'=> $request->land_topology ?? $farm_land_data->land_topology, 
            'land_gradient'=> $request->land_gradient ?? $farm_land_data->land_gradient, 
        ];

        if (!empty($farm_photo)) {
            $data_farm_land['farm_photo'] = implode(',', $farm_photo);
        }

        if (!empty($land_document)) {
            $data_farm_land['land_document'] = implode(',', $land_document);
        }
        
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

    public function get_cultivation($id)  {
        $farm_land = FarmLand::with([
            'cultivation' => function($query) {
                $query->where('cultivations.status', Cultivations::STATUS_ACTIVE);
            },
            'cultivation.season',
            'cultivation.crops_master',
            ])->find($id);
        return response()->json([
            'result' => true,
            'message' => 'Get Culitavtion Successfully',
            'data' =>[
                'cultivation'=>$farm_land->cultivation()->where('cultivations.status', Cultivations::STATUS_ACTIVE)->get(),
            ]
        ]);

    }

    public function create_log($data)
    {
        // dd($data);
        $staff = Auth::user()->staff;
        $log_actitvities = new LogActivitiesController();
        $data_log_activities = [
            'staff_id' => $staff->id ?? 0,
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
