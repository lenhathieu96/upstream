<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogActivitiesController;
use App\Http\Requests\GetSaleIntentionOrdersRequest;
use App\Models\CarbonEmission;
use App\Models\Cultivations;
use App\Models\FarmerDetails;
use App\Models\VendorProcurementDetail;
use App\Services\SaleIntentionService;
use Illuminate\Support\Facades\Http;
use App\Models\SaleIntention;
use App\Models\SRPFertilizerApplication;
use App\Models\SRPLandPreparation;
use App\Models\SRPPesticideApplication;
use App\Models\SRPSchedule;
use App\Models\SRPWaterIrrigation;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class SaleIntentionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $staff = Auth::user()->staff;
        $farmer_id = FarmerDetails::where('staff_id',$staff->id)->get();
        $data_sale = SaleIntention::whereIn('farmer_id',$farmer_id->pluck('id'))->with('farmer')->orderBy('created_at', 'DESC')->get();
        return response()->json
       ([
        'result' => true,
        'message' => 'Get data Successfully',
        'data'=>
            [
                'data_sale_intention'=>$data_sale,
            ]
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

       try {

           $preHarvestQc = $request->filled('pre_harvest_qc') ? array_map(function ($item) use ($request) {
               return [
                   'id' => isset($item['id']) ? (int)$item['id'] : null,
                   'description' => isset($item['description']) ? $item['description'] : null,
                   'unit' => isset($item['unit']) ? $item['unit'] : null,
                   'description_en' => isset($item['description_en']) ? $item['description_en'] : null,
                   'description_vn' => isset($item['description_vn']) ? $item['description_vn'] : null,
                   'min_standard' => isset($item['min_standard']) ? (int)$item['min_standard'] : null,
                   'max_standard' => isset($item['max_standard']) ? (int)$item['max_standard'] : null,
                   'value' => isset($item['value']) ? $item['value'] : null,
                   'type' => isset($item['type']) ? (int)$item['type'] : null,
               ];
           }, $request->pre_harvest_qc) : null;
                $sale_intention = new SaleIntention();
                $data_sale = [
                    'farmer_id'=>$request->farmer_id,
                    'farm_land_id'=>$request->farm_land_id,
                    'cultivation_id'=>$request->cultivation_id,
                    'season_id'=>$request->season_id,
                    'variety'=>$request->variety,
                    'sowing_date'=>$request->sowing_date,
                    'quantity'=>$request->quantity,
                    'min_price'=>$request->min_price,
                    'max_price'=>$request->max_price,
                    'date_for_harvest'=>$request->date_for_harvest,
                    'aviable_date'=>$request->aviable_date,
                    'grade'=>$request->grade,
                    'age_of_crop'=>$request->age_of_crop,
                    'quality_check'=>$request->quality_check,
                    'product_id'=>$request->product_id,
                    'photo'=>$request->thumbnail_img,
                    'lat' => $request->input('lat'),
                    'lng' => $request->input('lng'),
                    'pre_harvest_qc' => $preHarvestQc,
                ];
                // return response()->json([
                //     'result' => true,
                //     'message' => 'Sale Intention has been inserted successfully',
                //     'data'=>[
                //         'sale_intention'=>$data_sale,
                //     ]
                // ]);
                $final_data = $sale_intention->create($data_sale);
                return response()->json([
                    'result' => true,
                    'message' => 'Sale Intention has been inserted successfully',
                    'data'=>[
                        'sale_intention'=>$final_data,
                    ]
                ]);
        }
        catch (\Exception $exception) {
            return response()->json([
                'result' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
       $data_sale_intention = SaleIntention::where('product_id',$id)->first();
       if(isset($data_sale_intention->cultivation))
       {
            $data_carbon_emission = $data_sale_intention->cultivation->carbon_emission;
            if(isset($data_carbon_emission))
            {
                    $data_emission = $data_carbon_emission->emission;
                    $data_product_loss = $data_carbon_emission->product_loss;
                    $data_carbon_stage = $data_carbon_emission->carbon_stage;
            }
            else
            {
                    $data_emission = null;
                    $data_product_loss = null;
                    $data_carbon_stage = null;
            }
       }
       else
       {
            $data_emission = null;
            $data_product_loss = null;
            $data_carbon_stage = null;
       }
       
        $arr_date_and_cost_land_prepare = [];
        $arr_date_and_cost_water_igiration = [];
        $arr_date_and_cost_fertilize = [];
        $arr_date_and_cost_pesticise = [];

        $total_cost_land_preaparation =0;
        $total_cost_water_irrigration = 0;
        $total_cost_fetilizer = 0;
        $total_cost_srp_petisise = 0;


        $arr_date_and_score_training = [];
        $arr_date_and_score_pre_planting = [];
        $arr_date_and_score_water_management = [];
        $arr_date_and_score_nutrient_management = [];
        $arr_date_and_score_integrated_pest_management = [];
        $arr_date_and_score_pre_harvest = [];
        $arr_date_and_score_health_and_safety = [];
        $arr_date_and_score_labour_right = [];
        $arr_date_and_score_women_empowerment = [];
        if(isset($data_sale_intention->farmer))
        {
            if($data_sale_intention->farmer->srp_certification == 1)
            {
                    $array_schedule = ['srp_water_irrigation','srp_fertilizer_application','srp_pesticide_application','srp_land_preparation'];
                    $array_score = ['srp_training','srp_pre_planting','srp_water_management','srp_nutrient_management','srp_integrated_pest_management',"srp_harvest","srp_health_and_safety","srp_labour_right","srp_women_empowerment"];
                    $id_srp = $data_sale_intention->cultivation->srp->id;
                    $data_srp_schedule = SRPSchedule::where('srp_id',$id_srp)->where(function($query) use($array_schedule){
                        foreach($array_schedule as $keyword) {
                            $query->orWhere('name_action', 'LIKE', "%$keyword%");
                        }
                    })->get();
                
                    $data_srp_scores = SRPSchedule::where('srp_id',$id_srp)->where(function($query) use($array_score){
                        foreach($array_score as $keyword) {
                            $query->orWhere('name_action', 'LIKE', "%$keyword%");
                        }
                    })->get();

                    foreach($data_srp_schedule as $sub_data_each_srp)
                    {
                        switch($sub_data_each_srp->name_action)
                        {
                            case(str_contains($sub_data_each_srp->name_action,'srp_land_preparation')):
                                $data_each_srp_land_prepare = SRPLandPreparation::where([['srp_id',$id_srp],['question', 'LIKE', "total_cost_of_day"]])->whereDate('created_at',$sub_data_each_srp->date_action)->first();
                                if($data_each_srp_land_prepare)
                                {
                                    $arr_date_and_cost_land_prepare[$sub_data_each_srp->date_action] = (int)$data_each_srp_land_prepare->answer;
                                    $total_cost_land_preaparation +=(int)$data_each_srp_land_prepare->answer;
                                }
                                else
                                {
                                    $arr_date_and_cost_land_prepare[$sub_data_each_srp->date_action] = 0;
                                }
                                
                                break;
                            case(str_contains($sub_data_each_srp->name_action,'srp_water_irrigation')):
                                $data_each_srp_land_prepare = SRPWaterIrrigation::where([['srp_id',$id_srp],['question', 'LIKE', "total_cost_of_day"]])->whereDate('created_at',$sub_data_each_srp->date_action)->first();
                                if($data_each_srp_land_prepare)
                                {
                                    $arr_date_and_cost_water_igiration[$sub_data_each_srp->date_action] = (int)$data_each_srp_land_prepare->answer;
                                    $total_cost_water_irrigration +=(int)$data_each_srp_land_prepare->answer;
                                }
                                else
                                {
                                    $arr_date_and_cost_water_igiration[$sub_data_each_srp->date_action] = 0;
                                }
                                
                                break;
                            case(str_contains($sub_data_each_srp->name_action,'srp_fertilizer_application')):
                                $data_each_srp_land_prepare = SRPFertilizerApplication::where([['srp_id',$id_srp],['question', 'LIKE', "total_cost_of_day"]])->whereDate('created_at',$sub_data_each_srp->date_action)->first();
                                if($data_each_srp_land_prepare)
                                {
                                    $arr_date_and_cost_fertilize[$sub_data_each_srp->date_action] = (int)$data_each_srp_land_prepare->answer;
                                    $total_cost_fetilizer +=(int)$data_each_srp_land_prepare->answer;
                                }
                                else
                                {
                                    $arr_date_and_cost_fertilize[$sub_data_each_srp->date_action] = 0;
                                }
                            
                                break;
                            case(str_contains($sub_data_each_srp->name_action,'srp_pesticide_application')):
                                $data_each_srp_land_prepare = SRPPesticideApplication::where([['srp_id',$id_srp],['question', 'LIKE', "total_cost_of_day"]])->whereDate('created_at',$sub_data_each_srp->date_action)->first();
                                if($data_each_srp_land_prepare)
                                {
                                    $arr_date_and_cost_pesticise[$sub_data_each_srp->date_action] = (int)$data_each_srp_land_prepare->answer;
                                    $total_cost_srp_petisise +=(int)$data_each_srp_land_prepare->answer;
                                }
                                else
                                {
                                    $arr_date_and_cost_pesticise[$sub_data_each_srp->date_action] = 0;
                                }
                                break;
                        }
                    }

                    foreach($data_srp_scores as $data_srp_score)
                    {
                        switch($data_srp_score->name_action)
                        {
                            case(str_contains($data_srp_score->name_action,'srp_training')):
                                $arr_date_and_score_training[$data_srp_score->date_action] = (int)$data_srp_score->score;
                                
                                break;
                            case(str_contains($data_srp_score->name_action,'srp_pre_planting')):
                                $arr_date_and_score_pre_planting[$data_srp_score->date_action] = (int)$data_srp_score->score;
                                
                                break;
                            case(str_contains($data_srp_score->name_action,'srp_water_management')):
                                $arr_date_and_score_water_management[$data_srp_score->date_action] = (int)$data_srp_score->score;
                                
                                break;
                            case(str_contains($data_srp_score->name_action,'srp_nutrient_management')):
                                $arr_date_and_score_nutrient_management[$data_srp_score->date_action] = (int)$data_srp_score->score;
                                
                                break;
                            case(str_contains($data_srp_score->name_action,'srp_integrated_pest_management')):
                                $arr_date_and_score_integrated_pest_management[$data_srp_score->date_action] = (int)$data_srp_score->score;
                                
                                break;
                            case(str_contains($data_srp_score->name_action,'srp_harvest')):
                                $arr_date_and_score_pre_harvest[$data_srp_score->date_action] = (int)$data_srp_score->score;
                                
                                break;
                            case(str_contains($data_srp_score->name_action,'srp_health_and_safety')):
                                $arr_date_and_score_health_and_safety[$data_srp_score->date_action] = (int)$data_srp_score->score;
                                
                                break;
                            case(str_contains($data_srp_score->name_action,'srp_labour_right')):
                                $arr_date_and_score_labour_right[$data_srp_score->date_action] = (int)$data_srp_score->score;
                                
                                break;
                            case(str_contains($data_srp_score->name_action,'srp_women_empowerment')):
                                $arr_date_and_score_women_empowerment[$data_srp_score->date_action] = (int)$data_srp_score->score;
                                
                                break;
                        }
                    }
            }
            return response()->json
            ([
                'result' => true,
                'message' => 'Get data Successfully',
                'data'=>
                    [
                        'data_sale_intention'=>$data_sale_intention,
                        'data_farmer'=>$data_sale_intention->farmer,
                        'data_farm_land'=>$data_sale_intention->farm_land,
                        'data_cultivation'=>$data_sale_intention->cultivation,
                        'data_season'=>$data_sale_intention->season,
                        'data_emission'=>$data_emission,
                        'data_product_loss'=>$data_product_loss,
                        'data_carbon_stage'=>$data_carbon_stage,
                        'arr_date_and_cost_land_prepare' =>$arr_date_and_cost_land_prepare,
                        'arr_date_and_cost_water_igiration' =>$arr_date_and_cost_water_igiration,
                        'arr_date_and_cost_fertilize' =>$arr_date_and_cost_fertilize,
                        'arr_date_and_cost_pesticise' =>$arr_date_and_cost_pesticise,
                        'total_cost_land_preaparation' =>$total_cost_land_preaparation,
                        'total_cost_water_irrigration' =>$total_cost_water_irrigration,
                        'total_cost_fetilizer' =>$total_cost_fetilizer,
                        'total_cost_srp_petisise' =>$total_cost_srp_petisise,
                        'arr_date_and_score_training'=>$arr_date_and_score_training,
                        'arr_date_and_score_pre_planting' =>$arr_date_and_score_pre_planting,
                        'arr_date_and_score_water_management' =>$arr_date_and_score_water_management,
                        'arr_date_and_score_nutrient_management' =>$arr_date_and_score_nutrient_management,
                        'arr_date_and_score_integrated_pest_management' =>$arr_date_and_score_integrated_pest_management,
                        'arr_date_and_score_pre_harvest' =>$arr_date_and_score_pre_harvest,
                        'arr_date_and_score_health_and_safety' =>$arr_date_and_score_health_and_safety,
                        'arr_date_and_score_labour_right' =>$arr_date_and_score_labour_right,
                        'arr_date_and_score_women_empowerment' =>$arr_date_and_score_women_empowerment,
                    ]
            ]);
        }
        return response()->json
            ([
                'result' => true,
                'message' => 'Get data Successfully',
                'data'=>
                    [
                        
                    ]
            ]);
       
    }



    public function details_by_id($id)
    {
       $data_sale_intention = SaleIntention::find($id);
       $data_carbon_emission = $data_sale_intention->cultivation->carbon_emission;
       if(isset($data_carbon_emission))
       {
            $data_emission = $data_carbon_emission->emission;
            $data_product_loss = $data_carbon_emission->product_loss;
            $data_carbon_stage = $data_carbon_emission->carbon_stage;
       }
       else
       {
            $data_emission = null;
            $data_product_loss = null;
            $data_carbon_stage = null;
       }
       $arr_date_and_cost_land_prepare = [];
       $arr_date_and_cost_water_igiration = [];
       $arr_date_and_cost_fertilize = [];
       $arr_date_and_cost_pesticise = [];

        $total_cost_land_preaparation =0;
        $total_cost_water_irrigration = 0;
        $total_cost_fetilizer = 0;
        $total_cost_srp_petisise = 0;
       if($data_sale_intention->farmer->srp_certification == 1)
       {
            $array_schedule = ['srp_water_irrigation','srp_fertilizer_application','srp_pesticide_application','srp_land_preparation'];
            $id_srp = $data_sale_intention->cultivation->srp->id;
            $data_srp_schedule = SRPSchedule::where('srp_id',$id_srp)->where(function($query) use($array_schedule){
                foreach($array_schedule as $keyword) {
                    $query->orWhere('name_action', 'LIKE', "%$keyword%");
                }
            })->get();
          
            foreach($data_srp_schedule as $sub_data_each_srp)
            {
                switch($sub_data_each_srp->name_action)
                {
                    case(str_contains($sub_data_each_srp->name_action,'srp_land_preparation')):
                        $data_each_srp_land_prepare = SRPLandPreparation::where([['srp_id',$id_srp],['question', 'LIKE', "total_cost_of_day"]])->whereDate('created_at',$sub_data_each_srp->date_action)->first();
                        if($data_each_srp_land_prepare)
                        {
                            $arr_date_and_cost_land_prepare[$sub_data_each_srp->date_action] = (int)$data_each_srp_land_prepare->answer;
                            $total_cost_land_preaparation +=(int)$data_each_srp_land_prepare->answer;
                        }
                        else
                        {
                            $arr_date_and_cost_land_prepare[$sub_data_each_srp->date_action] = 0;
                        }
                        
                        break;
                    case(str_contains($sub_data_each_srp->name_action,'srp_water_irrigation')):
                        $data_each_srp_land_prepare = SRPWaterIrrigation::where([['srp_id',$id_srp],['question', 'LIKE', "total_cost_of_day"]])->whereDate('created_at',$sub_data_each_srp->date_action)->first();
                        if($data_each_srp_land_prepare)
                        {
                            $arr_date_and_cost_water_igiration[$sub_data_each_srp->date_action] = (int)$data_each_srp_land_prepare->answer;
                            $total_cost_water_irrigration +=(int)$data_each_srp_land_prepare->answer;
                        }
                        else
                        {
                            $arr_date_and_cost_water_igiration[$sub_data_each_srp->date_action] = 0;
                        }
                        
                        break;
                    case(str_contains($sub_data_each_srp->name_action,'srp_fertilizer_application')):
                        $data_each_srp_land_prepare = SRPFertilizerApplication::where([['srp_id',$id_srp],['question', 'LIKE', "total_cost_of_day"]])->whereDate('created_at',$sub_data_each_srp->date_action)->first();
                        if($data_each_srp_land_prepare)
                        {
                            $arr_date_and_cost_fertilize[$sub_data_each_srp->date_action] = (int)$data_each_srp_land_prepare->answer;
                            $total_cost_fetilizer +=(int)$data_each_srp_land_prepare->answer;
                        }
                        else
                        {
                            $arr_date_and_cost_fertilize[$sub_data_each_srp->date_action] = 0;
                        }
                       
                        break;
                    case(str_contains($sub_data_each_srp->name_action,'srp_pesticide_application')):
                        $data_each_srp_land_prepare = SRPPesticideApplication::where([['srp_id',$id_srp],['question', 'LIKE', "total_cost_of_day"]])->whereDate('created_at',$sub_data_each_srp->date_action)->first();
                        if($data_each_srp_land_prepare)
                        {
                            $arr_date_and_cost_pesticise[$sub_data_each_srp->date_action] = (int)$data_each_srp_land_prepare->answer;
                            $total_cost_srp_petisise +=(int)$data_each_srp_land_prepare->answer;
                        }
                        else
                        {
                            $arr_date_and_cost_pesticise[$sub_data_each_srp->date_action] = 0;
                        }
                        break;
                }
            }
       }
       return response()->json
       ([
        'result' => true,
        'message' => 'Get data Successfully',
        'data'=>
            [
                'data_sale_intention'=>$data_sale_intention,
                'data_farmer'=>$data_sale_intention->farmer,
                'data_farm_land'=>$data_sale_intention->farm_land,
                'data_cultivation'=>$data_sale_intention->cultivation,
                'data_season'=>$data_sale_intention->season,
                'data_emission'=>$data_emission,
                'data_product_loss'=>$data_product_loss,
                'data_carbon_stage'=>$data_carbon_stage,
                'arr_date_and_cost_land_prepare' =>$arr_date_and_cost_land_prepare,
                'arr_date_and_cost_water_igiration' =>$arr_date_and_cost_water_igiration,
                'arr_date_and_cost_fertilize' =>$arr_date_and_cost_fertilize,
                'arr_date_and_cost_pesticise' =>$arr_date_and_cost_pesticise,
                'total_cost_land_preaparation' =>$total_cost_land_preaparation,
                'total_cost_water_irrigration' =>$total_cost_water_irrigration,
                'total_cost_fetilizer' =>$total_cost_fetilizer,
                'total_cost_srp_petisise' =>$total_cost_srp_petisise,
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaleIntention $saleIntention)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaleIntention $saleIntention)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaleIntention $saleIntention)
    {
        //
    }

    public function create_log($data)
    {
        // dd($data);
        $staff = Auth::user()->staff;
        $log_actitvities = new LogActivitiesController();
        $data_log_activities = [
            'staff_id' => $staff?->id,
            'type' => 308,
            'action'=>$data->action,
            'request'=>$data->request,
            'status_code'=>$data->status_code,
            'status_msg'=>$data->status_msg,
            'lat'=>$data->lat,
            'lng'=>$data->lng
        ];
        $log_actitvities->store_log((object) $data_log_activities);
    }

    public function getSaleIntentionOrders(GetSaleIntentionOrdersRequest $request)
    {
        $productIds = (new SaleIntentionService())->getStaffSaleIntentionColumn('product_id');
        $saleIntentionIds = (new SaleIntentionService())->getStaffSaleIntentionColumn('id');
        $exceptIds = VendorProcurementDetail::whereIn('sale_intention_id', $saleIntentionIds)->pluck('product_id')->toArray();
        $ids = array_diff($productIds, $exceptIds);

        $endpoint = config('upstream.HEROMARKET_URL') . '/api/v2/order/auction_products';

        $response = Http::withOptions(['verify' => false])->post($endpoint, ['product_ids' => $ids]);

        return json_decode($response->getBody(), true);
    }
}
