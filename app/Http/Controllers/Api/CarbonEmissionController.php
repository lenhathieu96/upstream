<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\CarbonEmission;
use App\Models\CarbonStage;
use App\Models\Emission;
use App\Models\ProductLoss;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarbonEmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    private $lo_Harv;
    private $lo_Dry;
    private $lo_Sto;
    private $lo_Mill;
    private $percent_Harv;
    private $percent_Dry;
    private $percent_Sto;
    private $percent_Mill;
    private $gwp_ch4 = 28;
    private $gwp_n2o = 265;


    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $carbon_emission = New CarbonEmission();
        $emission = New Emission();
        $product_loss = new ProductLoss();
        $carbon_stage = new CarbonStage();
        $data =(object) $request->data_carbon_emmission;
        $this->lo_Harv = 1 - ($data->harv_grain_loss_rate)/100;
        $this->lo_Dry = 1 - ($data->dry_grain_loss_rate)/100;
        $this->lo_Sto = 1 - ($data->sto_grain_loss_rate)/100;
        $this->lo_Mill = 1 - ($data->mil_grain_loss_rate)/100;
        $this->percent_Harv = ($data->harv_grain_loss_rate)/100;
        $this->percent_Dry = ($data->dry_grain_loss_rate)/100;
        $this->percent_Sto = ($data->sto_grain_loss_rate)/100;
        $this->percent_Mill = ($data->mil_grain_loss_rate)/100;
        $Q_data = $this->quantity_for_carbon_emission((object)$data);
        $ghg_data = $this->ghg_for_carbon_emission((object)$data , (object)$Q_data);
        $all_Q_data = (object)$Q_data;
        $all_ghg_data = (object)$ghg_data;
        // Create Master Data Carbon Emission
        $data_carbon_emission = [
            'farmer_id'=>$request->farmer_id,
            'farmland_id'=>$request->farmland_id,
            'cultivation_id'=>$request->cultivation_id,
            'season_id'=>$request->season_id,
            'staff_id'=>Auth::user()->staff->id
        ];
        $final_carbon_emission =  $carbon_emission->create($data_carbon_emission);
        // Create Master Data Emission
        $data_emission = [
            'carbon_emissions_id'=>$final_carbon_emission->id,
            'cultivation'=>round($all_ghg_data->ghg_Cult,2),
            'hgh'=>round($all_ghg_data->ghg_Hph,2),
            'crop_establish'=>round($all_ghg_data->ghg_Wet+$all_ghg_data->ghg_Seed+$all_ghg_data->ghg_Pet,2),
            'water_soil'=>round($all_ghg_data->ghg_Flood,2),
            'fetilizer'=>round((round($all_ghg_data->ghg_N2o,2) + $all_ghg_data->ghg_Co2),2),
            'equipment'=>round(($all_ghg_data->ghg_Pump + $all_ghg_data->ghg_Mac),2),
            'harvesting'=>round($all_ghg_data->ghg_Harv,2),
            'straw_management'=>round($all_ghg_data->ghg_Straw,2),
            'drying'=>round($all_ghg_data->ghg_Dry,2),
            'storing'=>round($all_ghg_data->ghg_Sto,2),
            'milling'=>round($all_ghg_data->ghg_Mill,2),
            'packaging'=>round($all_ghg_data->ghg_Pack,2),
            'transports'=>round($all_ghg_data->ghg_Trans,2),
            'co2_emission'=>round($all_ghg_data->t_ghg_Co2,2),
            'ch4_emission'=>round($all_ghg_data->ghg_Ch4,2),
            'n20_emission'=>round($all_ghg_data->ghg_N2o,2),
            'ghg_emission'=>round($all_ghg_data->ghg_Tot,2),
            'carbon_foot_print'=>round($all_ghg_data->cf_Prod,2)
        ];
        $final_emission =  $emission->create($data_emission);

        // Create Master Data Product Loss
        $data_product_loss = [
            'carbon_emissions_id'=>$final_carbon_emission->id,
            'yield_before_harvest'=>round($all_Q_data->Q_Base,2),
            'harvesting_losses'=>round((round($all_Q_data->Q_Base,2) - round($all_Q_data->Q_Harv,2)),1),
            'drying_losses'=>round((round($all_Q_data->Q_Harv,2) - round($all_Q_data->Q_Dry,2)),1),
            'storing_losses'=>round((round($all_Q_data->Q_Dry,2) - round($all_Q_data->Q_Sto,2)),2),
            'milling_losses'=>round((round($all_Q_data->Q_Loss,2) - (round($all_Q_data->Q_Base,2) - round($all_Q_data->Q_Harv,2))- (round($all_Q_data->Q_Harv,2) - round($all_Q_data->Q_Dry,2)) - (round($all_Q_data->Q_Dry,2) - round($all_Q_data->Q_Sto,2))),2),
            'food_losses'=>round($all_Q_data->Q_Loss,2),
            'husk'=>round($all_Q_data->Q_Husk,2),
            'bran'=>round($all_Q_data->Q_Bran,2),
            'rice_straw'=>round($all_Q_data->var_Straw,2),
            'rice_husk'=>round($all_Q_data->var_Husk,2),
            'rice_bran'=>round($all_Q_data->var_Bran,2),
            'total_product_loss'=>round($all_Q_data->Q_Base,2) - (round($all_Q_data->Q_Husk,2) + round($all_Q_data->Q_Bran,2) + round($all_Q_data->Q_Loss,2) + round($all_Q_data->var_Husk,2) + round($all_Q_data->var_Bran,2))
        ];
        $final_data_product_loss =  $product_loss->create($data_product_loss);

        // Create master data for carbon_stage
        $data_carbon_stage = [
            'carbon_emissions_id'=>$final_carbon_emission->id,
            'crop_establish'=>round((($final_emission->crop_establish) * round($all_ghg_data->cf_Prod,2)/round($all_ghg_data->ghg_Tot,2)),2) ,
            'water_soil'=>round((($final_emission->water_soil) * round($all_ghg_data->cf_Prod,2)/round($all_ghg_data->ghg_Tot,2)),2) ,
            'fetilizer'=>round((($final_emission->fetilizer) * round($all_ghg_data->cf_Prod,2)/round($all_ghg_data->ghg_Tot,2)),2) ,
            'equipment'=>round((($final_emission->equipment) * round($all_ghg_data->cf_Prod,2)/round($all_ghg_data->ghg_Tot,2)),2) ,
            'harvesting'=>round((($final_emission->harvesting) * round($all_ghg_data->cf_Prod,2)/round($all_ghg_data->ghg_Tot,2)),2) ,
            'straw_management'=>round((($final_emission->straw_management) * round($all_ghg_data->cf_Prod,2)/round($all_ghg_data->ghg_Tot,2)),2) ,
            'drying'=>round((($final_emission->drying) * round($all_ghg_data->cf_Prod,2)/round($all_ghg_data->ghg_Tot,2)),2) ,
            'storing'=>round((($final_emission->storing) * round($all_ghg_data->cf_Prod,2)/round($all_ghg_data->ghg_Tot,2)),2) ,
            'milling'=>round((($final_emission->milling) * round($all_ghg_data->cf_Prod,2)/round($all_ghg_data->ghg_Tot,2)),2) ,
            'packaging'=>round((($final_emission->packaging) * round($all_ghg_data->cf_Prod,2)/round($all_ghg_data->ghg_Tot,2)),2) ,
            'transports'=>round((($final_emission->transports) * round($all_ghg_data->cf_Prod,2)/round($all_ghg_data->ghg_Tot,2)),2) ,
        ];
        $final_data_carbon_stage =  $carbon_stage->create($data_carbon_stage);

        return response()->json([
            'result' => true,
            'message' => 'Create Carbon Footprint Successfully',
            'data' =>[
                'data_carbon_emission' =>$final_carbon_emission,
                'data_emission' =>$final_emission,
                'data_product_loss' => $final_data_product_loss,
                'final_data_carbon_stage' => $data_carbon_stage
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $carbon_emission = CarbonEmission::find($id);
        return response()->json([
            'result' => true,
            'message' => 'Get Carbon Footprint Successfully',
            'data' =>[
                'data_carbon_emission' =>$carbon_emission,
                'data_emission' =>$carbon_emission->emission,
                'data_product_loss' => $carbon_emission->product_loss,
                'final_data_carbon_stage' => $carbon_emission->carbon_stage
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CarbonEmission $carbonEmission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CarbonEmission $carbonEmission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CarbonEmission $carbonEmission)
    {
        //
    }

    public function quantity_for_carbon_emission($data)
    {
        
        $Q_base = ($data->est_yield) * (((1-($data->moisure_content/100))/(1-0.14))/($this->lo_Harv));
        $Q_straw = ($data->est_yield) * 0.6;
        $Q_Harv =  $Q_base * $this->lo_Harv;
        $Q_Dry =    $Q_base * $this->lo_Harv  *  $this->lo_Dry;
        $Q_Sto =  $Q_base * $this->lo_Harv  *  $this->lo_Dry * ($this->lo_Sto);
        $Q_Mill =  $Q_base *($this->lo_Mill - ($data->mil_rice_husk/100) - ($data->mil_rice_bran/100))  * $this->lo_Harv  *  $this->lo_Dry * ($this->lo_Sto) ;
        $Q_Husk = $Q_Sto * ($data->mil_rice_husk/100);
        $Q_Bran = $Q_Sto * ($data->mil_rice_bran/100);
        $Q_Prod = $Q_Mill;
        $var_Straw = ($data->sale_mil_rice == 0 ?0:(0 * $data->sale_rice_straw / $data->sale_mil_rice));
       
        $var_Husk = ($data->sale_mil_rice == 0 ?0:($Q_Husk * $data->sale_rice_husk / $data->sale_mil_rice));
      
        $var_Bran =round(($data->sale_mil_rice == 0 ?0:($Q_Bran * $data->sale_rice_bran / $data->sale_mil_rice)),9);
       
        $Q_Bp =($data->sale_mil_rice == 0 ?0:($var_Husk + $var_Bran + $var_Straw));
       
        $Q_Loss = ($Q_base * $this->percent_Harv) + ($Q_Harv * $this->percent_Dry ) + ($Q_Dry * $this->percent_Sto ) + ($Q_Sto * $this->percent_Mill);
        return $data=[
            'Q_Base'=>round($Q_base, 9),
            'Q_Harv'=>$Q_Harv,
            'Q_Dry'=>$Q_Dry,
            'Q_Sto'=>$Q_Sto,
            'Q_Mill'=>$Q_Mill,
            'Q_Straw'=>$Q_straw,
            'Q_Husk'=>$Q_Husk,
            'Q_Bran'=>$Q_Bran,
            'Q_Prod'=>$Q_Prod,
            'Q_Bp'=>round($Q_Bp,9),
            'Q_Loss'=>round($Q_Loss,9),
            'var_Husk'=>$var_Husk,
            'var_Bran'=>$var_Bran,
            'var_Straw'=>$var_Straw,
        ];
    }


    public function ghg_for_carbon_emission($data , $Q_data)
    {
        $ghg_Harv = $data->harv_emission_potential;
        $ghg_Straw = $data->percent_of_straw * $Q_data->Q_Straw * $data->straw_management;
        $ghg_Dry = $data->dry_emission_potential * $Q_data->Q_Harv;
        $ghg_Sto = $data->sto_emission_potential * $Q_data->Q_Dry;
        $ghg_Mill = $data->mil_emission_potential * $Q_data->Q_Sto;
        $ghg_Pack = $data->rice_pakaging  * $Q_data->Q_Mill;
        $ghg_Trans = (($data->truck *0.4) + ($data->tractor *0.257) + ($data->local_boat *0.2) + ($data->ship *0.0225))*$Q_data->Q_Prod ;
        $ghg_Wet = $data->soil_wet;
        $ghg_Seed = $data->seed_type * $data->seed_rate;
        $ghg_Pet = $data->pesticide_user;
        $sub_data_for_flood = pow((1 + ($data->amount_of_staw_inco * $data->timing_of_staw_inco) + ($data->type_organic * $data->amount_organic)),0.59);
        $ghg_Flood = $data->cultiavtion_period * $data->methane_emission_factor * $this->gwp_ch4 * $data->pre_season_water * $data->in_season_water * $sub_data_for_flood;
        $ghg_N2o = ($data->in_season_water == 1 ? 0.3:0.5)/100 * $this->gwp_n2o * 1.571429 * $data->n_rate;
        $ghg_Co2 = $data->co2_from_n_fetilizer  * $data->n_rate;
        $ghg_Pump = $data->water_pump;
        $ghg_Mac = $data->filed_operation;
        $ghg_Tot = $ghg_Harv + $ghg_Straw + $ghg_Dry + $ghg_Sto + $ghg_Mill + $ghg_Pack + $ghg_Trans + $ghg_Wet + $ghg_Seed + $ghg_Pet + $ghg_Flood + $ghg_N2o + $ghg_Co2 + $ghg_Pump + $ghg_Mac;
        $ghg_Cult = $ghg_Wet + $ghg_Seed + $ghg_Pet + $ghg_Flood + $ghg_N2o + $ghg_Co2 + $ghg_Pump + $ghg_Mac;
        $ghg_Hph = $ghg_Harv + $ghg_Straw + $ghg_Dry + $ghg_Sto + $ghg_Mill + $ghg_Pack + $ghg_Trans;
        $ghg_Ch4 = $ghg_Flood;
        $t_ghg_N2o = $ghg_N2o;
        $t_ghg_Co2 =$ghg_Tot - $ghg_Ch4 - $t_ghg_N2o;
        $cf_Prod =$ghg_Tot /($Q_data->Q_Prod + $Q_data->Q_Bp);
        $ghg_Intensity =$ghg_Tot/$Q_data->Q_Base;

        return $data=[
            // 'ghg_Harv'=>round($ghg_Harv, 9),
           'ghg_Harv' => $ghg_Harv,
           'ghg_Straw' => $ghg_Straw,
           'ghg_Dry' => $ghg_Dry ,
           'ghg_Sto' => $ghg_Sto ,
           'ghg_Mill' => $ghg_Mill ,
           'ghg_Pack' => $ghg_Pack ,
           'ghg_Trans' => $ghg_Trans ,
           'ghg_Wet' => $ghg_Wet,
           'ghg_Seed' => $ghg_Seed ,
           'ghg_Pet' => $ghg_Pet ,
           'ghg_Flood' => $ghg_Flood ,
           'ghg_N2o' => $ghg_N2o ,
           'ghg_Co2' => $ghg_Co2,
           'ghg_Pump' => $ghg_Pump ,
           'ghg_Mac' => $ghg_Mac ,
           'ghg_Tot' => round($ghg_Tot,6) ,
           'ghg_Cult' => round($ghg_Cult,6),
           'ghg_Hph' => round($ghg_Hph,5) ,
           'ghg_Ch4' => $ghg_Ch4,
           't_ghg_N2o' => round($t_ghg_N2o,7) ,
           't_ghg_Co2' => round($t_ghg_Co2,5) ,
           'cf_Prod' => round($cf_Prod,6) ,
           'ghg_Intensity' => round($ghg_Intensity,7)
        ];
    }

  
}
