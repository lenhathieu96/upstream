<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetCropsRequest;
use App\Http\Requests\GetCultivationsRequest;
use App\Models\CropInformation;
use App\Models\Cultivations;
use App\Models\FarmLand;
use App\Models\Season;
use App\Models\SeasonMaster;
use App\Models\SRP;
use App\Models\SRPSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CultivationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetCultivationsRequest $request)
    {
        $cultivation = Cultivations::query()->orderBy('id', 'desc')->where('status', Cultivations::STATUS_ACTIVE);
        $filterBy = [
            'farm_land_id',
            'season_id',
            'crop_id',
            'crop_variety',
        ];
        foreach ($filterBy as $item) {
            if ($request->filled($item)) {
                $cultivation->where($item, $request->input($item));
            }
        }

        if ($request->filled('whereDoesntHave')) {
            $cultivation->whereDoesntHave($request->input('whereDoesntHave'));
        }

        return $this->success($cultivation->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getListCrop(GetCropsRequest $request): JsonResponse
    {
        $season = SeasonMaster::all();
        $crop_information  = CropInformation::orderBy('id', 'desc');
        if ($request->filled('farm_land_id')) {
            $crop_information->whereHas('cultivations', function ($query) use ($request) {
                $query->where('farm_land_id', '=', $request->input('farm_land_id'));
            });
        }
        if ($request->filled('season_id')) {
            $crop_information->whereHas('cultivations', function ($query) use ($request) {
                $query->where('season_id', '=', $request->input('season_id'));
            });
        }
        $farm_land = Auth::user()->staff->farm_land_count()->where('farm_lands.status', FarmLand::STATUS_ACTIVE)->get();
        return response()->json([
            'result' => true,
            'message' => 'Get Data Crops Successfully',
            'data'=> [
                'season' =>$season,
                'crop_information'=> $crop_information->get(),
                'farm_land'=>$farm_land
            ]
           
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data_log_activities = [];
        $data_log_activities['action'] = 'create';
        $data_log_activities['request'] = $request->all();
        $data_log_activities['lat'] = $request->staff_lat;
        $data_log_activities['lng'] = $request->staff_lng;
        $validator = Validator::make($request->all(), [
            'farm_land_id' => 'required|string',
            'season_id' => 'required|string',
            'crop_master_id' => 'required|string',
            'crop_variety' => 'required|string',
            'sowing_date' => 'required|string',
            'expect_date' => 'required|string',
        ]);
        if ($validator->fails()) {
            $str_validation = "";
            foreach ($validator->messages()->messages() as $key => $data)
            {
                $str_validation .= $data[0].",";
            }
            $data_log_activities['status_code'] = 400;
            $data_log_activities['status_msg'] = $str_validation;
            try {
                $this->create_log((object) $data_log_activities);
            } catch (\Exception $e) {  
            
            }
            return response()->json([
                'result' => false,
                'message' => $validator->messages(),
            ]);
        }
        $user = Auth::user();
        
        $crops = new Cultivations();
        $data_crops = [
            'farm_land_id'=>$request->farm_land_id,
            'season_id'=>$request->season_id,
            'crop_id'=>$request->crop_master_id,
            'crop_variety'=>$request->crop_variety,
            'sowing_date'=>$request->sowing_date,
            'expect_date'=>$request->expect_date,
            'est_yield'=>$request->est_yield,
            //'photo'=>implode(',', $crop_photo), 
        ];

        
        try 
        {
            $final_crops = $crops->create($data_crops);
            if($final_crops)
            {
                $crop_photo = [];
                if (!empty($request->all()['photo'])) {
                    
                    foreach ($request->all()['photo'] as $photo) {                        
                        $id = (new UploadsController)->upload_photo($photo,$final_crops->id, 'cultivation');
                        if (!empty($id)) {
                            array_push($crop_photo, $id);
                        }
                    }    
                }
                $final_crops->photo = implode(',', $crop_photo);
                $final_crops->save();

                $data__farmer = $final_crops?->farm_land?->farmer_details;
                if($data__farmer->srp_certification == 1)
                {
                    $data_each_action_srp = [
                        "srp_training" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(-7)->toDateString('d/m/Y'),
                        "srp_pre_planting" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(-3)->toDateString('d/m/Y'),
                        "srp_land_preparation" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(-10)->toDateString('d/m/Y'),
                        "srp_water_management" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(0)->toDateString('d/m/Y'),
                        "srp_water_irrigation" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(-3)->toDateString('d/m/Y'),
                        "srp_water_irrigation_2" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(3)->toDateString('d/m/Y'),
                        "srp_water_irrigation_3" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(10)->toDateString('d/m/Y'),
                        "srp_water_irrigation_4" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(17)->toDateString('d/m/Y'),
                        "srp_water_irrigation_5" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(25)->toDateString('d/m/Y'),
                        "srp_water_irrigation_6" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(36)->toDateString('d/m/Y'),
                        "srp_water_irrigation_7" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(45)->toDateString('d/m/Y'),
                        "srp_water_irrigation_8" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(55)->toDateString('d/m/Y'),
                        "srp_water_irrigation_9" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(65)->toDateString('d/m/Y'),
                        "srp_water_irrigation_10" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(75)->toDateString('d/m/Y'),
                        "srp_nutrient_management" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(55)->toDateString('d/m/Y'),
                        "srp_fertilizer_application" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(10)->toDateString('d/m/Y'),
                        "srp_fertilizer_application_2" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(25)->toDateString('d/m/Y'),
                        "srp_fertilizer_application_3" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(35)->toDateString('d/m/Y'),
                        "srp_fertilizer_application_4" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(45)->toDateString('d/m/Y'),
                        "srp_integrated_pest_management" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(75)->toDateString('d/m/Y'),
                        "srp_pesticide_application" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(7)->toDateString('d/m/Y'),
                        "srp_pesticide_application_2" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(15)->toDateString('d/m/Y'),
                        "srp_pesticide_application_3" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(26)->toDateString('d/m/Y'),
                        "srp_pesticide_application_4" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(35)->toDateString('d/m/Y'),
                        "srp_pesticide_application_5" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(45)->toDateString('d/m/Y'),
                        "srp_pesticide_application_6" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(60)->toDateString('d/m/Y'),
                        "srp_pesticide_application_7" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(75)->toDateString('d/m/Y'),
                        "srp_harvest" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(95)->toDateString('d/m/Y'),
                        "srp_harvest_2" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(100)->toDateString('d/m/Y'),
                        "srp_health_and_safety" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(100)->toDateString('d/m/Y'),
                        "srp_labour_right" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(100)->toDateString('d/m/Y'),
                        "srp_women_empowerment" => Carbon::createFromFormat('d/m/Y', $request->sowing_date)->addDay(100)->toDateString('d/m/Y'),
                    ];
                    $srps = new SRP();
                    $data_srps = [
                        'farmer_id'=>$request->farmer_id,
                        'staff_id'=>$user->staff->id,
                        'farm_land_id'=>$request->farm_land_id,
                        'season_id'=>$request->season_id,
                        'cultivation_id'=>$final_crops->id,
                        'score'=>0,
                        'sowing_date'=>$request->sowing_date,
                    ];
                    
                    $final_srps = $srps->create($data_srps);
                    foreach($data_each_action_srp as $key =>$details)
                    {
                        $srp_schedule = new SRPSchedule();
                        $data_srp_schedule = 
                        [
                            'srp_id'=>$final_srps->id,
                            'name_action'=>$key,
                            'date_action'=>$details,
                        ];
                        $final_srp_schedule = $srp_schedule->create($data_srp_schedule);
                    }
                    $data_log_activities['status_code'] = 200;
                    $data_log_activities['status_msg'] = 'Crops Created Successfully';
                    $this->create_log((object) $data_log_activities);
                    return response()->json([
                        'result' => true,
                        'message' => 'Crops Created Successfully',
                        'data'=> [
                            'data_crops' =>$final_crops,
                        ]
                        
                    ]);
                }
                else
                {
                    return response()->json([
                        'result' => true,
                        'message' => 'Crops Created Successfully',
                        'data'=> [
                            'data_crops' =>$final_crops,
                        ]
                        
                    ]);
                }
                
            }
            // if($final_crops)
            
        } catch (\Exception $e) {  
            $data_log_activities['status_code'] = 400;
            $data_log_activities['status_msg'] = $e->getMessage();
            $this->create_log((object) $data_log_activities);
            return response()->json([
                'result' => false,
                'message' => $e,
            ]);
        }
        // else
        // {
        //     return response()->json([
        //         'result' => false,
        //         'message' => 'Crops Created Fail'
        //     ]);
        // }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $crop_data = Cultivations::find($id);
        $farm_land = $crop_data->farm_land()->get();
        $season_master = $crop_data->season()->get();
        $crop_master = $crop_data->crops_master()->get();
        return response()->json([
            'result' => true,
            'message' => 'Get Data Cultivations Successfully',
            'data'=> [
                'cultivation_data' =>$crop_data,
                'farm_land' =>$farm_land,
                'season_master' =>$season_master,
                'crop_master' =>$crop_master,
                'carbon_emission_id'=>$crop_data->carbon_emission?->id,
                'srp_id'=>$crop_data->srp?->id
            ]
           
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cultivations $crops)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $crop_data = Cultivations::find($id);
        $data_log_activities = [];
        $data_log_activities['action'] = 'update';
        $data_log_activities['request'] = $request->all();
        $data_log_activities['lat'] = $request->staff_lat;
        $data_log_activities['lng'] = $request->staff_lng;
        $validator = Validator::make($request->all(), [
            'farm_land_id' => 'required|string',
            'season_id' => 'required|string',
            'crop_master_id' => 'required|string',
            'crop_variety' => 'required|string',
            'sowing_date' => 'required|string',
            'expect_date' => 'required|string',
        ]);
        if ($validator->fails()) {
            $str_validation = "";
            foreach ($validator->messages()->messages() as $key => $data)
            {
                $str_validation .= $data[0].",";
            }
            $data_log_activities['status_code'] = 400;
            $data_log_activities['status_msg'] = $str_validation;
            try {
                $this->create_log((object) $data_log_activities);
            } catch (\Exception $e) {  
            
            }
            return response()->json([
                'result' => false,
                'message' => $validator->messages(),
            ]);
        }
        $user = Auth::user();
        $crop_photo = [];
        if (!empty($request->all()['photo'])) {
            
            foreach ($request->all()['photo'] as $photo) {                        
                $id = (new UploadsController)->upload_photo($photo,$crop_data->id, 'cultivation');
                if (!empty($id)) {
                    array_push($crop_photo, $id);
                }
            }    
        }
        // $crops = new Cultivations();
        $data_crops = [
            'farm_land_id'=>$request->farm_land_id ?? $crop_data->farm_land_id,
            'season_id'=>$request->season_id,
            'crop_id'=>$request->crop_master_id,
            'crop_variety'=>$request->crop_variety,
            'sowing_date'=>$request->sowing_date,
            'expect_date'=>$request->expect_date,
            'est_yield'=>$request->est_yield,
            'photo'=> !empty($crop_photo) ? implode(',', $crop_photo) : $crop_data->photo, 
        ];
        try 
        {
            $final_crops = $crop_data->update($data_crops);
            if($final_crops)
            {
                $data_log_activities['status_code'] = 200;
                $data_log_activities['status_msg'] = 'Crops Created Successfully';
                $this->create_log((object) $data_log_activities);
                return response()->json([
                    'result' => true,
                    'message' => 'Crops Updated Successfully',
                    'data'=> [
                        'data_crops' =>$final_crops,
                    ]
                    
                ]);
            }
        } catch (\Exception $e) {  
            $data_log_activities['status_code'] = 400;
            $data_log_activities['status_msg'] = $e->getMessage();
            $this->create_log((object) $data_log_activities);
            return response()->json([
                'result' => true,
                'message' => 'Farm Updated Fail',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cultivations $crops)
    {
        //
    }

    public function get_crop_variety($id)
    {
        $crop_information = CropInformation::find($id);
        return response()->json([
            'result' => true,
            'message' => 'Get Crop Variety Successfully',
            'data'=> [
                'crop_variety' =>$crop_information->crop_variety,
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
            'type' => 357,
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
