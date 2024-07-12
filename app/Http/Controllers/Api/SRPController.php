<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FarmerDetails;
use App\Models\NutrientManagement;
use App\Models\SRP;
use App\Models\SRPFarmManagement;
use App\Models\SRPIntegratedPestManagement;
use App\Models\SRPFertilizerApplication;
use App\Models\SRPFieldVisit;
use App\Models\SRPHarvest;
use App\Models\SRPHealthAndSafety;
use App\Models\SRPLabourRight;
use App\Models\SRPLandPreparation;
use App\Models\SRPPesticideApplication;
use App\Models\SRPPrePlanting;
use App\Models\SRPSchedule;
use App\Models\SRPTraining;
use App\Models\SRPWaterIrrigation;
use App\Models\SRPWaterManagement;
use App\Models\SRPWomenEmpowerment;
use App\Models\Uploads;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SRPController extends Controller
{
    public function srpUploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:10000',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        $staff = Auth::user()->staff;
        $id = (new UploadsController)->upload_photo($request->photo, $staff->id, 'staff');

        $uploadFile = Uploads::find($id);
        if ($uploadFile) {
            return response()->json([
                'message' => 'success',
                'data' => [
                    'url' => asset($uploadFile->file_name),
                ]
            ]);
        }

        return response()->json([
            'message' => 'fail',
            'data' => [
                'url' => '',
            ]
        ]);
    }

    public function storeLandPreparation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srp_id' => 'required|exists:srps,id',
            'data_question_answer_group' => 'required|array',
        ]);
        $today = Carbon::createFromFormat('d/m/Y', $request->date_action);
        if ($validator->fails()) {
            return $validator->messages();
        }
        
        $staff = Auth::user()->staff;
        $total_cost = 0;
        foreach($request->data_question_answer_group as $groupData) {
            $collectionCode = SRPLandPreparation::max('collection_code') ?? 0;
            $latestCollectionCode = $collectionCode + 1;

            foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;
                $type = isset($data['type']) ? $data['type'] : "";
                $title = isset($data['title']) ? $data['title'] : "";

                if(str_contains($key, 'total_cost'))
                {
                    $total_cost += (int)$answer;
                }
                SRPLandPreparation::create([
                    'farmer_id' => $request->farmer_id,
                    'cultivation_id' => $request->cultivation_id,
                    'staff_id'=> $staff->id,
                    'srp_id' => $request->srp_id,
                    'section' => $data['section'],
                    'collection_code' => $latestCollectionCode,
                    'question'=> $key,
                    'title'=> $title,
                    'type' => $type,
                    'answer'=> $answer,
                    'score' => $score,
                    'created_at'=>$today,
                ]);

            }
        }
        SRPLandPreparation::create([
            'farmer_id' => $request->farmer_id,
            'cultivation_id' => $request->cultivation_id,
            'staff_id'=> $staff->id,
            'srp_id' => $request->srp_id,
            'section' => "",
            'collection_code' =>0,
            'question'=> "total_cost_of_day",
            'title'=> "",
            'type' => "",
            'answer'=> $total_cost,
            'score' => 0,
            'created_at'=>$today,
        ]);
        $data_schedule_srp = SRPSchedule::where([['name_action','srp_land_preparation'],['srp_id',$request->srp_id]])->whereDate('date_action',$today)->first();
        if($data_schedule_srp)
        {
            try
            {
                $data_schedule_srp->update(['is_finished'=>1]);
            }
            catch (\Exception $exception) 
            {

            }
           
        }

        return response()->json([
            'result' => true,
            'message' => 'SRP Land Preparation Created Successfully',
        ]);
    }

    

    public function storeFarmManagement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srp_id' => 'required|exists:srps,id',
            'data_question_answer_group' => 'required|array',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }
        
        $staff = Auth::user()->staff;
        $total_score = 0;
        
        foreach($request->data_question_answer_group as $groupData) {
            foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;

                SRPFarmManagement::create([
                    'farmer_id' => $request->farmer_id,
                    'cultivation_id' => $request->cultivation_id,
                    'staff_id'=> $staff->id,
                    'srp_id' => $request->srp_id,
                    'question'=> $key,
                    'answer'=> $answer,
                    'score' => $score
                ]);

                $total_score += $score;
            }
        }

        $srp = SRP::find($request->srp_id);
        $srp->score += $total_score;
        $srp->save();

        return response()->json([
            'result' => true,
            'message' => 'SRP Farm Management Created Successfully',
        ]);
    }

    public function storeTraining(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srp_id' => 'required|exists:srps,id',
            'data_question_answer_group' => 'required|array',
        ]);
        $today = Carbon::createFromFormat('d/m/Y', $request->date_action);
        
        if ($validator->fails()) {
            return $validator->messages();
        }
        
        $staff = Auth::user()->staff;
        $total_score = 0;
        
       
        foreach($request->data_question_answer_group as $groupData) {
            foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;
                $type = isset($data['type']) ? $data['type'] : "";
                $title = isset($data['title']) ? $data['title'] : "";

                SRPTraining::create([
                    'farmer_id' => $request->farmer_id,
                    'cultivation_id' => $request->cultivation_id,
                    'staff_id'=> $staff->id,
                    'srp_id' => $request->srp_id,
                    'question'=> $key,
                    'title'=> $title,
                    'type' => $type,
                    'answer'=> $answer,
                    'score' => $score,
                    'created_at'=>$today,
                ]);

                $total_score += $score;
            }
        }

        $srp = SRP::find($request->srp_id);
        $srp->score += $total_score;
        $srp->save();
        $data_schedule_srp = SRPSchedule::where([['name_action','srp_training'],['srp_id',$request->srp_id]])->whereDate('date_action',$today)->first();
        if($data_schedule_srp)
        {
            try
            {
                $data_schedule_srp->update(['is_finished'=>1,'score'=>$total_score]);
            }
            catch (\Exception $exception) 
            {

            }
           
        }
        return response()->json([
            'result' => true,
            'message' => 'SRP Training Created Successfully',
        ]);
    }

    public function storeWaterManagement(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srp_id' => 'required|exists:srps,id',
            'data_question_answer_group' => 'required|array',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }
        $today = Carbon::createFromFormat('d/m/Y', $request->date_action);
        $staff = Auth::user()->staff;
        $total_score = 0;
        $idFirstOfWaterManagement = 0;
        foreach($request->data_question_answer_group as $groupData) {
            foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;
                $type = isset($data['type']) ? $data['type'] : "";
                $title = isset($data['title']) ? $data['title'] : "";
                $srpWaterManagement = SRPWaterManagement::create([
                    'farmer_id' => $request->farmer_id,
                    'cultivation_id' => $request->cultivation_id,
                    'staff_id'=> $staff->id,
                    'srp_id' => $request->srp_id,
                    'question'=> $key,
                    'title'=> $title,
                    'type' => $type,
                    'answer'=> $answer,
                    'score' => $score,
                    'created_at'=>$today,
                ]);
                $total_score += $score;
            }
        }

        $srp = SRP::find($request->srp_id);
        $srp->score += $total_score;
        $srp->save();
        $data_schedule_srp = SRPSchedule::where([['name_action','srp_water_management'],['srp_id',$request->srp_id]])->whereDate('date_action',$today)->first();
        if($data_schedule_srp)
        {
            try
            {
                $data_schedule_srp->update(['is_finished'=>1,'score'=>$total_score]);
            }
            catch (\Exception $exception) 
            {

            }
           
        }
        return response()->json([
            'result' => true,
            'message' => 'SRP Water Management Created Successfully',
        ]);
    }

    public function storeWaterIrrigation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srp_id' => 'required|exists:srps,id',
            'data_question_answer_group' => 'required|array',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }
        $today = Carbon::createFromFormat('d/m/Y', $request->date_action);
        
        $staff = Auth::user()->staff;
        $total_cost = 0;
        
        foreach($request->data_question_answer_group as $groupData) {
            $collectionCode = SRPWaterIrrigation::max('collection_code') ?? 0;
            $latestCollectionCode = $collectionCode + 1;

            foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;
                $type = isset($data['type']) ? $data['type'] : "";
                $title = isset($data['title']) ? $data['title'] : "";
                if(str_contains($key, 'total_cost'))
                {
                    $total_cost += (int)$answer;
                }
                SRPWaterIrrigation::create([
                    'farmer_id' => $request->farmer_id,
                    'cultivation_id' => $request->cultivation_id,
                    'staff_id'=> $staff->id,
                    'srp_id' => $request->srp_id,
                    'section' => $data['section'],
                    'collection_code' => $latestCollectionCode,
                    'question'=> $key,
                    'title'=> $title,
                    'type' => $type,
                    'answer'=> $answer,
                    'score' => $score,
                    'created_at'=>$today,
                ]);
            }
        }
        SRPWaterIrrigation::create([
            'farmer_id' => $request->farmer_id,
            'cultivation_id' => $request->cultivation_id,
            'staff_id'=> $staff->id,
            'srp_id' => $request->srp_id,
            'section' => "",
            'collection_code' =>0,
            'question'=> "total_cost_of_day",
            'title'=> "",
            'type' => "",
            'answer'=> $total_cost,
            'score' => 0,
            'created_at'=>$today,
        ]);
        $data_schedule_srp = SRPSchedule::where([['name_action', 'like', '%' .'srp_water_irrigation' . '%'],['srp_id',$request->srp_id]])->whereDate('date_action',$today)->first();
        if($data_schedule_srp)
        {
            try
            {
                $data_schedule_srp->update(['is_finished'=>1,'score'=>0]);
            }
            catch (\Exception $exception) 
            {

            }
           
        }
        return response()->json([
            'result' => true,
            'message' => 'SRP Water Irrigation Created Successfully',
        ]);
    }

    public function storePesticideApplication(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srp_id' => 'required|exists:srps,id',
            'data_question_answer_group' => 'required|array',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }
        $total_cost = 0;
        $staff = Auth::user()->staff;
        $today = Carbon::createFromFormat('d/m/Y', $request->date_action);
        foreach($request->data_question_answer_group as $groupData) {
            $collectionCode = SRPPesticideApplication::max('collection_code') ?? 0;
            $latestCollectionCode = $collectionCode + 1;

            foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;
                $type = isset($data['type']) ? $data['type'] : "";
                $title = isset($data['title']) ? $data['title'] : "";
                if(str_contains($key, 'total_cost'))
                {
                    $total_cost += (int)$answer;
                }
                SRPPesticideApplication::create([
                    'farmer_id' => $request->farmer_id,
                    'cultivation_id' => $request->cultivation_id,
                    'staff_id'=> $staff->id,
                    'srp_id' => $request->srp_id,
                    'section' => $data['section'],
                    'collection_code' => $latestCollectionCode,
                    'question'=> $key,
                    'title'=> $title,
                    'type' => $type,
                    'answer'=> $answer,
                    'score' => $score,
                    'created_at'=>$today,
                ]);
            }
        }
        SRPPesticideApplication::create([
            'farmer_id' => $request->farmer_id,
            'cultivation_id' => $request->cultivation_id,
            'staff_id'=> $staff->id,
            'srp_id' => $request->srp_id,
            'section' => "",
            'collection_code' =>0,
            'question'=> "total_cost_of_day",
            'title'=> "",
            'type' => "",
            'answer'=> $total_cost,
            'score' => 0,
            'created_at'=>$today,
        ]);
        $data_schedule_srp = SRPSchedule::where([['name_action', 'like', '%' .'srp_pesticide_application' . '%'],['srp_id',$request->srp_id]])->whereDate('date_action',$today)->first();
        if($data_schedule_srp)
        {
            try
            {
                $data_schedule_srp->update(['is_finished'=>1,'score'=>0]);
            }
            catch (\Exception $exception) 
            {

            }
           
        }
        return response()->json([
            'result' => true,
            'message' => 'SRP Pesticide Application Created Successfully',
        ]);
    }

    public function storePrePlanting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srp_id' => 'required|exists:srps,id',
            'data_question_answer_group' => 'required|array',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }
        $today = Carbon::createFromFormat('d/m/Y', $request->date_action);
        $staff = Auth::user()->staff;
        $total_score = 0;
        foreach($request->data_question_answer_group as $groupData) {
            foreach($groupData as $key => $data) {
            // dd($groupData);
            // foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;
                $type = isset($data['type']) ? $data['type'] : "";
                $title = isset($data['title']) ? $data['title'] : "";
                SRPPrePlanting::create([
                    'farmer_id' => $request->farmer_id,
                    'cultivation_id' => $request->cultivation_id,
                    'staff_id'=> $staff->id,
                    'srp_id' => $request->srp_id,
                    'question'=> $key,
                    'title'=> $title,
                    'type' => $type,
                    'answer'=> $answer,
                    'score' => $score,
                    'created_at'=>$today,
                ]);
                $total_score += $score;
            }
        }

        $srp = SRP::find($request->srp_id);
        $srp->score += $total_score;
        $srp->save();
        $data_schedule_srp = SRPSchedule::where([['name_action','srp_pre_planting'],['srp_id',$request->srp_id]])->whereDate('date_action',$today)->first();
        if($data_schedule_srp)
        {
            try
            {
                $data_schedule_srp->update(['is_finished'=>1,'score'=>$total_score]);
            }
            catch (\Exception $exception) 
            {

            }
           
        }
        return response()->json([
            'result' => true,
            'message' => 'SRP Pre-planting Created Successfully',
        ]);
    }

    // Nutrient Managemet 
    public function storeNutrientManagement(Request $request)
    {
        // dd($request);
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srp_id' => 'required|exists:srps,id',
            'data_question_answer_group' => 'required|array',
        ]);

        $id_water_management = 0;
        if ($validator->fails()) {
            return $validator->messages();
        }
        $today = Carbon::createFromFormat('d/m/Y', $request->date_action);
        $staff = Auth::user()->staff;
        $total_score = 0;
        foreach($request->data_question_answer_group as $groupData) {
            // dd($groupData);
            foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;
                $type = isset($data['type']) ? $data['type'] : "";
                $title = isset($data['title']) ? $data['title'] : "";
                NutrientManagement::create([
                    'farmer_id' => $request->farmer_id,
                    'cultivation_id' => $request->cultivation_id,
                    'staff_id'=> $staff->id,
                    'srp_id' => $request->srp_id,
                    'question'=> $key,
                    'answer'=> $answer,
                    'score' => $score,
                    'title'=> $title,
                    'type' => $type,
                    'created_at'=>$today,
                ]);
               
                $total_score += $score;
            }
        }

        $srp = SRP::find($request->srp_id);
        $srp->score += $total_score;
        $srp->save();
        $data_schedule_srp = SRPSchedule::where([['name_action','srp_nutrient_management'],['srp_id',$request->srp_id]])->whereDate('date_action',$today)->first();
        if($data_schedule_srp)
        {
            try
            {
                $data_schedule_srp->update(['is_finished'=>1,'score'=>$total_score]);
            }
            catch (\Exception $exception) 
            {

            }
           
        }
        return response()->json([
            'result' => true,
            'message' => 'SRP Nutrient Management Created Successfully',
        ]);
    }

    // Integrated Pest Management 
    public function storeIntegratedPestManagement(Request $request)
    {
        
        // dd($request);
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srp_id' => 'required|exists:srps,id',
            'data_question_answer_group' => 'required|array',
        ]);

        $id_water_management = 0;
        if ($validator->fails()) {
            return $validator->messages();
        }
        $today = Carbon::createFromFormat('d/m/Y', $request->date_action);
        $staff = Auth::user()->staff;
        $total_score = 0;
        foreach($request->data_question_answer_group as $groupData) {
            // dd($groupData);
            foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;
                $type = isset($data['type']) ? $data['type'] : "";
                $title = isset($data['title']) ? $data['title'] : "";
                SRPIntegratedPestManagement::create([
                    'farmer_id' => $request->farmer_id,
                    'cultivation_id' => $request->cultivation_id,
                    'staff_id'=> $staff->id,
                    'srp_id' => $request->srp_id,
                    'question'=> $key,
                    'answer'=> $answer,
                    'title'=> $title,
                    'type' => $type,
                    'score' => $score,
                    'created_at'=>$today,
                ]);
            
                $total_score += $score;
            }
        }

        $srp = SRP::find($request->srp_id);
        $srp->score += $total_score;
        $srp->save();
        $data_schedule_srp = SRPSchedule::where([['name_action','srp_integrated_pest_management'],['srp_id',$request->srp_id]])->whereDate('date_action',$today)->first();
        if($data_schedule_srp)
        {
            try
            {
                $data_schedule_srp->update(['is_finished'=>1,'score'=>$total_score]);
            }
            catch (\Exception $exception) 
            {

            }
           
        }
        return response()->json([
            'result' => true,
            'message' => 'SRP Integrated Pest Management Created Successfully',
        ]);
    }

    // Fertilizer Application
    public function storeFertilizerApplication(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srp_id' => 'required|exists:srps,id',
            'data_question_answer_group' => 'required|array',
        ]);

        $id_water_management = 0;
        if ($validator->fails()) {
            return $validator->messages();
        }
        $today = Carbon::createFromFormat('d/m/Y', $request->date_action);
        $staff = Auth::user()->staff;
        $total_cost = 0;
        
        foreach($request->data_question_answer_group as $groupData) {
            $collectionCode = SRPFertilizerApplication::max('collection_code') ?? 0;
            $latestCollectionCode = $collectionCode + 1;

            foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;
                $type = isset($data['type']) ? $data['type'] : "";
                $title = isset($data['title']) ? $data['title'] : "";

                if(str_contains($key, 'total_cost'))
                {
                    $total_cost += (int)$answer;
                }
                SRPFertilizerApplication::create([
                    'farmer_id' => $request->farmer_id,
                    'cultivation_id' => $request->cultivation_id,
                    'staff_id'=> $staff->id,
                    'srp_id' => $request->srp_id,
                    'section' => $data['section'],
                    'collection_code' => $latestCollectionCode,
                    'question'=> $key,
                    'answer'=> $answer,
                    'score' => $score,
                    'title'=> $title,
                    'type' => $type,
                    'created_at'=>$today,
                ]);
            }
        }
        SRPFertilizerApplication::create([
            'farmer_id' => $request->farmer_id,
            'cultivation_id' => $request->cultivation_id,
            'staff_id'=> $staff->id,
            'srp_id' => $request->srp_id,
            'section' => "",
            'collection_code' =>0,
            'question'=> "total_cost_of_day",
            'title'=> "",
            'type' => "",
            'answer'=> $total_cost,
            'score' => 0,
            'created_at'=>$today,
        ]);
        $data_schedule_srp = SRPSchedule::where([['name_action', 'like', '%' .'srp_fertilizer_application' . '%'],['srp_id',$request->srp_id]])->whereDate('date_action',$today)->first();
        if($data_schedule_srp)
        {
            try
            {
                $data_schedule_srp->update(['is_finished'=>1,'score'=>0]);
            }
            catch (\Exception $exception) 
            {

            }
           
        }
        return response()->json([
            'result' => true,
            'message' => 'SRP Fertilizer Application Created Successfully',
        ]);
    }

    // Harvest
    public function storeHarvest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srp_id' => 'required|exists:srps,id',
            'data_question_answer_group' => 'required|array',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }
        $today = Carbon::createFromFormat('d/m/Y', $request->date_action);
        $staff = Auth::user()->staff;
        $total_score = 0;

        foreach($request->data_question_answer_group as $groupData) {
            $collectionCode = SRPHarvest::max('collection_code') ?? 0;
            $latestCollectionCode = $collectionCode + 1;

            foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;
                $type = isset($data['type']) ? $data['type'] : "";
                $title = isset($data['title']) ? $data['title'] : "";
                SRPHarvest::create([
                    'farmer_id' => $request->farmer_id,
                    'cultivation_id' => $request->cultivation_id,
                    'staff_id'=> $staff->id,
                    'srp_id' => $request->srp_id,
                    'section' => $data['section'],
                    'title'=> $title,
                    'type' => $type,
                    'collection_code' => $latestCollectionCode,
                    'question'=> $key,
                    'answer'=> $answer,
                    'score' => $score
                ]);
                $total_score += $score;
            }
        }

        $srp = SRP::find($request->srp_id);
        $srp->score += $total_score;
        $srp->save();
        $data_schedule_srp = SRPSchedule::where([['name_action', 'like', '%' .'srp_harvest' . '%'],['srp_id',$request->srp_id]])->whereDate('date_action',$today)->first();
        if($data_schedule_srp)
        {
            try
            {
                $data_schedule_srp->update(['is_finished'=>1,'score'=>$total_score]);
            }
            catch (\Exception $exception) 
            {

            }
           
        }
        return response()->json([
            'result' => true,
            'message' => 'SRP Havest Created Successfully',
        ]);
    }

    public function storeLabourRight(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srp_id' => 'required|exists:srps,id',
            'data_question_answer_group' => 'required|array',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }
        
        $staff = Auth::user()->staff;
        $total_score = 0;

        foreach($request->data_question_answer_group as $groupData) {
            $collectionCode = SRPLabourRight::max('collection_code') ?? 0;
            $latestCollectionCode = $collectionCode + 1;

            foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;

                SRPLabourRight::create([
                    'farmer_id' => $request->farmer_id,
                    'cultivation_id' => $request->cultivation_id,
                    'staff_id'=> $staff->id,
                    'srp_id' => $request->srp_id,
                    'section' => $data['section'],
                    'collection_code' => $latestCollectionCode,
                    'question'=> $key,
                    'answer'=> $answer,
                    'score' => $score
                ]);

                $total_score += $score;
            }
        }

        $srp = SRP::find($request->srp_id);
        $srp->score += $total_score;
        $srp->save();

        return response()->json([
            'result' => true,
            'message' => 'SRP Labour Right Created Successfully',
        ]);
    }

    // Health And Safety
    public function storeHealthAndSafety(Request $request)
    {
        // dd($request);
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srp_id' => 'required|exists:srps,id',
            'data_question_answer_group' => 'required|array',
        ]);

        $id_water_management = 0;
        if ($validator->fails()) {
            return $validator->messages();
        }
        
        $staff = Auth::user()->staff;
        $today = Carbon::createFromFormat('d/m/Y', $request->date_action);
        $total_score = 0;
        foreach($request->data_question_answer_group as $groupData) {
            // dd($groupData);
            foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;
                $type = isset($data['type']) ? $data['type'] : "";
                $title = isset($data['title']) ? $data['title'] : "";
                SRPHealthAndSafety::create([
                    'farmer_id' => $request->farmer_id,
                    'cultivation_id' => $request->cultivation_id,
                    'staff_id'=> $staff->id,
                    'srp_id' => $request->srp_id,
                    'question'=> $key,
                    'title'=> $title,
                    'type' => $type,
                    'answer'=> $answer,
                    'score' => $score
                ]);
            
                $total_score += $score;
            }
        }

        $srp = SRP::find($request->srp_id);
        $srp->score += $total_score;
        $srp->save();
        $data_schedule_srp = SRPSchedule::where([['name_action', 'srp_health_and_safety'],['srp_id',$request->srp_id]])->whereDate('date_action',$today)->first();
        if($data_schedule_srp)
        {
            try
            {
                $data_schedule_srp->update(['is_finished'=>1,'score'=>$total_score]);
            }
            catch (\Exception $exception) 
            {

            }
           
        }
        return response()->json([
            'result' => true,
            'message' => 'SRP Health And Safety Created Successfully',
        ]);
    }

    // Women Empowerment
    public function storeWomenEmpowerment(Request $request)
    {
        // dd($request);
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srp_id' => 'required|exists:srps,id',
            'data_question_answer_group' => 'required|array',
        ]);

        $id_water_management = 0;
        if ($validator->fails()) {
            return $validator->messages();
        }
        
        $staff = Auth::user()->staff;
        $total_score = 0;
        foreach($request->data_question_answer_group as $groupData) {
            // dd($groupData);
            foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;

                SRPWomenEmpowerment::create([
                    'farmer_id' => $request->farmer_id,
                    'cultivation_id' => $request->cultivation_id,
                    'staff_id'=> $staff->id,
                    'srp_id' => $request->srp_id,
                    'question'=> $key,
                    'answer'=> $answer,
                    'score' => $score
                ]);
            
                $total_score += $score;
            }
        }

        $srp = SRP::find($request->srp_id);
        $srp->score += $total_score;
        $srp->save();

        return response()->json([
            'result' => true,
            'message' => 'SRP Women Empowerment Successfully',
        ]);
    }

     //  Field Visit
     public function storeFieldVisit(Request $request)
     {
         // dd($request);
         $validator = Validator::make($request->all(), [
             'farmer_id' => 'required|exists:farmer_details,id',
             'cultivation_id' => 'required|exists:cultivations,id',
             'srp_id' => 'required|exists:srps,id',
             'data_question_answer_group' => 'required|array',
         ]);
 
         $id_water_management = 0;
         if ($validator->fails()) {
             return $validator->messages();
         }
         
         $staff = Auth::user()->staff;
         $total_score = 0;
         foreach($request->data_question_answer_group as $groupData) {
             // dd($groupData);
             foreach($groupData as $key => $data) {
                 $answer = !empty($data['answer']) ? $data['answer'] : "";
                 $score = !empty($data['score']) ? $data['score'] : 0;
 
                 SRPFieldVisit::create([
                     'farmer_id' => $request->farmer_id,
                     'cultivation_id' => $request->cultivation_id,
                     'staff_id'=> $staff->id,
                     'srp_id' => $request->srp_id,
                     'question'=> $key,
                     'answer'=> $answer,
                     'score' => $score
                 ]);
             
                 $total_score += $score;
             }
         }
 
         $srp = SRP::find($request->srp_id);
         $srp->score += $total_score;
         $srp->save();
 
         return response()->json([
             'result' => true,
             'message' => 'SRP Field Visit Successfully',
         ]);
     }


    // ========== Get api ================


    // Get Training
    public function getTraining(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'srp_id' => 'required|exists:srps,id',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        $staff = Auth::user()->staff;
        $today = Carbon::createFromFormat('d/m/Y', $request->date_action);
        $landPreparations = SRPTraining::where('srp_id', $request->srp_id)
            ->whereDate('created_at', $today)
            ->get(['question','answer','score','title','type']);
        $score = SRPTraining::where('srp_id', $request->srp_id)
        ->whereDate('created_at', $today)
        ->get(['question','answer','score','title','type'])->sum('score');
        return response()->json(['data' => $landPreparations]);
    }

    // Get Pre Plaining
    public function getPrePlanting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'srp_id' => 'required|exists:srps,id',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        $staff = Auth::user()->staff;
        $today = Carbon::createFromFormat('d/m/Y', $request->date_action);
        $landPreparations = SRPPrePlanting::where('srp_id', $request->srp_id)
            ->whereDate('created_at', $today)
            ->get(['question','answer','score','title','type']);
        $dataGroup = [];
        foreach($landPreparations as $landPreparation) {
            $dataGroup[] = $landPreparation;
        }

        return response()->json(['data' => $dataGroup]);
    }

    // Get Land Preparation
    public function getLandPreparation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'srp_id' => 'required|exists:srps,id',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        $staff = Auth::user()->staff;

        $today = Carbon::createFromFormat('d/m/Y', $request->date_action);
        $landPreparationBySections = SRPLandPreparation::where('srp_id', $request->srp_id)
            ->whereDate('created_at', $today)
            ->get();

        // $resultLandPreparationData = [];
        // foreach ($landPreparationBySections as $section => $landPreparationBySection) {
        //     $dataLandPreparation = [];
        //     $landPreparationByCollectionCodes = $landPreparationBySection->groupBy('collection_code');
            
        //     foreach ($landPreparationByCollectionCodes as $landPreparation) {
        //         array_push($dataLandPreparation, $landPreparation);
        //     }

        //     $resultLandPreparationData[$section] = $dataLandPreparation;
        // }

        return response()->json(['data'=> $landPreparationBySections]);
    }

    public function getWaterManagement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'srp_id' => 'required|exists:srps,id',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        $staff = Auth::user()->staff;

        $today = Carbon::createFromFormat('d/m/Y', $request->date_action);
        $waterManagement = SRPWaterManagement::where('srp_id', $request->srp_id)
            ->whereDate('created_at', $today)
            ->get(['question','answer','score','title','type']);

        return response()->json(['data'=> $waterManagement]);
    }

    public function getWaterIrrigation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'srp_id' => 'required|exists:srps,id',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        $staff = Auth::user()->staff;

        $today = Carbon::createFromFormat('d/m/Y', $request->date_action);
        $waterIrrigationBySections = SRPWaterIrrigation::where('srp_id', $request->srp_id)
            ->whereDate('created_at', $today)
            ->get();

        // $resultData = [];
        // foreach ($waterIrrigationBySections as $section => $waterIrrigationBySection) {
        //     $dataWaterIrrigation = [];
        //     $waterIrrigationByCollectionCodes = $waterIrrigationBySection->groupBy('collection_code');
            
        //     foreach ($waterIrrigationByCollectionCodes as $waterIrrigation) {
        //         array_push($dataWaterIrrigation, $waterIrrigation);
        //     }

        //     $resultData[$section] = $dataWaterIrrigation;
        // }

        return response()->json(['data'=> $waterIrrigationBySections]);
    }

    // Get Nutrient Management
    public function getNutrientManagement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'srp_id' => 'required|exists:srps,id',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        $staff = Auth::user()->staff;

        $today = Carbon::createFromFormat('d/m/Y', $request->date_action);
        $landPreparations = NutrientManagement::where('srp_id', $request->srp_id)
            ->whereDate('created_at', $today)
            ->get(['question','answer','score','title','type']);
            // ->groupBy('collection_code');

        return response()->json(['data'=> $landPreparations]);
    }

    public function getFertilizerApplication(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'srp_id' => 'required|exists:srps,id',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        $staff = Auth::user()->staff;
        $today = Carbon::createFromFormat('d/m/Y', $request->date_action);
        $fertilizerApplicationBySections = SRPFertilizerApplication::where('srp_id', $request->srp_id)
            ->whereDate('created_at', $today)
            ->get();

        // $resultData = [];
        // foreach ($fertilizerApplicationBySections as $section => $fertilizerApplicationBySection) {
        //     $datafertilizerApplication = [];
        //     $fertilizerApplicationByCollectionCodes = $fertilizerApplicationBySection->groupBy('collection_code');
            
        //     foreach ($fertilizerApplicationByCollectionCodes as $fertilizerApplication) {
        //         array_push($datafertilizerApplication, $fertilizerApplication);
        //     }

        //     $resultData[$section] = $datafertilizerApplication;
        // }

        return response()->json(['data'=> $fertilizerApplicationBySections]);
    }

    public function getIntegratedPestManagement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'srp_id' => 'required|exists:srps,id',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        $staff = Auth::user()->staff;
        $today = Carbon::createFromFormat('d/m/Y', $request->date_action);
        $landPreparations = NutrientManagement::where('srp_id', $request->srp_id)
            ->whereDate('created_at', $today)
            ->get(['question','answer','score','title','type']);
            // ->groupBy('collection_code');

        $dataGroup = [];
        foreach($landPreparations as $landPreparation) {
            $dataGroup[] = $landPreparation;
        }

        return response()->json(['data' => $dataGroup]);
    }

    public function getPesticideApplication(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'srp_id' => 'required|exists:srps,id',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        $staff = Auth::user()->staff;
        $today = Carbon::createFromFormat('d/m/Y', $request->date_action);
        $pesticideApplicationBySections = SRPPesticideApplication::where('srp_id', $request->srp_id)
            ->whereDate('created_at', $today)
            ->get();

        // $resultData = [];
        // foreach ($pesticideApplicationBySections as $section => $pesticideApplicationBySection) {
        //     $dataPesticideApplication = [];
        //     $pesticideApplicationByCollectionCodes = $pesticideApplicationBySection->groupBy('collection_code');
            
        //     foreach ($pesticideApplicationByCollectionCodes as $pesticideApplication) {
        //         array_push($dataPesticideApplication, $pesticideApplication);
        //     }

        //     $resultData[$section] = $dataPesticideApplication;
        // }

        return response()->json(['data'=> $pesticideApplicationBySections]);
    }

    public function getHarvest(Request $request)
    {
        $staff = Auth::user()->staff;
        $today = Carbon::createFromFormat('d/m/Y', $request->date_action);
        $landPreparations = SRPHarvest::where('srp_id', $request->srp_id)
            ->whereDate('created_at', $today)
            ->get(['question','answer','score','title','type']);
        return response()->json(['data' => $landPreparations]);
    }

    public function getHealthAndSafety(Request $request)
    {
        $staff = Auth::user()->staff;
        $today = Carbon::createFromFormat('d/m/Y', $request->date_action);
        $landPreparations = SRPHealthAndSafety::where('srp_id', $request->srp_id)
            ->whereDate('created_at', $today)
            ->get(['question','answer','score','title','type']);
        return response()->json(['data' => $landPreparations]);
    }

    public function getLabourRight(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srp_id' => 'required|exists:srps,id',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        $staff = Auth::user()->staff;

        $harvestBySections = SRPLabourRight::where('farmer_id', $request->farmer_id)
            ->where('cultivation_id', $request->cultivation_id)
            ->where('srp_id', $request->srp_id)
            ->where('staff_id', $staff->id)
            ->get()
            ->groupBy('section');

        $resultData = [];
        foreach ($harvestBySections as $section => $harvestBySection) {
            $dataHarvest = [];
            $harvestByCollectionCodes = $harvestBySection->groupBy('collection_code');
            
            foreach ($harvestByCollectionCodes as $harvest) {
                array_push($dataHarvest, $harvest);
            }

            $resultData[$section] = $dataHarvest;
        }

        return response()->json(['data'=> $resultData]);
    }

    public function getWomenEmpowerment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srp_id' => 'required|exists:srps,id',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        $staff = Auth::user()->staff;

        $landPreparations = SRPWomenEmpowerment::where('farmer_id', $request->farmer_id)
            ->where('cultivation_id', $request->cultivation_id)
            ->where('srp_id', $request->srp_id)
            ->where('staff_id', $staff->id)
            ->get(['question','answer','score']);
            // ->groupBy('collection_code');

        return response()->json(['data'=> $landPreparations]);
    }

    public function getFieldVisit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srp_id' => 'required|exists:srps,id',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        $staff = Auth::user()->staff;

        $landPreparations = SRPFieldVisit::where('farmer_id', $request->farmer_id)
            ->where('cultivation_id', $request->cultivation_id)
            ->where('srp_id', $request->srp_id)
            ->where('staff_id', $staff->id)
            ->get(['question','answer','score']);
            // ->groupBy('collection_code');

        return response()->json(['data'=> $landPreparations]);
    }

    public function getTaskStatus(Request $request)
    {
        $staff = Auth::user()->staff;

        $farmers = FarmerDetails::has('cultivation_crop')->where('staff_id', $staff->id)->get();
        
        $taskCompleted = [];
        $taskPending = [];
        foreach ($farmers as $farmer) {
            $cultivations = $farmer->cultivation_crop;
            foreach ($cultivations as $cultivation) {
                unset($farmer['cultivation_crop']);
                $isBaseOnSRP = $farmer->srp_certification;
                $task = [];
                $task['farmer'] = $farmer;
                $task['cultivation'] = $cultivation;

                if ($isBaseOnSRP) {
                    $totalTaskCompleted = $this->getSRPTaskCount($farmer->id, $cultivation->id, $staff->id);
                    $totalTaskPending = 14 - $totalTaskCompleted;

                    $taskCompleted[] = array_merge($task, ['total_completed' => $totalTaskCompleted]);
                    $taskPending[]   = array_merge($task, ['total_pending' => $totalTaskPending]);
                } else {
                    $totalTaskPending = $cultivation->crops_master->crop_stages->count();
                    unset($cultivation['crops_master']);
                    $totalTaskCompleted = SRPFieldVisit::where('farmer_id', $farmer->id)
                        ->where('cultivation_id', $cultivation->id)
                        ->where('staff_id', $staff->id)
                        ->count();

                    $taskCompleted[] = array_merge($task, ['total_completed' => $totalTaskCompleted]);
                    $taskPending[]   = array_merge($task, ['total_pending' => $totalTaskPending]);
                }
            }
        }

        return response()->json(['task_completed'=> $taskCompleted, 'task_pending' => $taskPending]);
    }

    public function getSRPTaskCount($farmerId, $cultivationId, $staffId)
    {
        $countFertilizerApplication = SRPFertilizerApplication::where('farmer_id', $farmerId)
            ->where('cultivation_id', $cultivationId)
            ->where('staff_id', $staffId)
            ->first() ? 1 : 0;

        $countFieldVisit = SRPFieldVisit::where('farmer_id', $farmerId)
            ->where('cultivation_id', $cultivationId)
            ->where('staff_id', $staffId)
            ->first() ? 1 : 0;

        $countHavest = SRPHarvest::where('farmer_id', $farmerId)
            ->where('cultivation_id', $cultivationId)
            ->where('staff_id', $staffId)
            ->first() ? 1 : 0;

        $countHealthAndSafety = SRPHealthAndSafety::where('farmer_id', $farmerId)
            ->where('cultivation_id', $cultivationId)
            ->where('staff_id', $staffId)
            ->first() ? 1 : 0;

        $countIntergratedPestManagement = SRPIntegratedPestManagement::where('farmer_id', $farmerId)
            ->where('cultivation_id', $cultivationId)
            ->where('staff_id', $staffId)
            ->first() ? 1 : 0;

        $countLabourRight = SRPLabourRight::where('farmer_id', $farmerId)
            ->where('cultivation_id', $cultivationId)
            ->where('staff_id', $staffId)
            ->first() ? 1 : 0;

        $countLandPreparation = SRPLandPreparation::where('farmer_id', $farmerId)
            ->where('cultivation_id', $cultivationId)
            ->where('staff_id', $staffId)
            ->first() ? 1 : 0;

        $countNutrientManagement = NutrientManagement::where('farmer_id', $farmerId)
            ->where('cultivation_id', $cultivationId)
            ->where('staff_id', $staffId)
            ->first() ? 1 : 0;

        $countPesticideApplication = SRPPesticideApplication::where('farmer_id', $farmerId)
            ->where('cultivation_id', $cultivationId)
            ->where('staff_id', $staffId)
            ->first() ? 1 : 0;

        $countPrePlanting = SRPPrePlanting::where('farmer_id', $farmerId)
            ->where('cultivation_id', $cultivationId)
            ->where('staff_id', $staffId)
            ->first() ? 1 : 0;

        $countTraining = SRPTraining::where('farmer_id', $farmerId)
            ->where('cultivation_id', $cultivationId)
            ->where('staff_id', $staffId)
            ->first() ? 1 : 0;

        $countWaterIrrigation = SRPWaterIrrigation::where('farmer_id', $farmerId)
            ->where('cultivation_id', $cultivationId)
            ->where('staff_id', $staffId)
            ->first() ? 1 : 0;

        $countWaterManagement = SRPWaterManagement::where('farmer_id', $farmerId)
            ->where('cultivation_id', $cultivationId)
            ->where('staff_id', $staffId)
            ->first() ? 1 : 0;

        $countWomenEmpowerment = SRPWomenEmpowerment::where('farmer_id', $farmerId)
            ->where('cultivation_id', $cultivationId)
            ->where('staff_id', $staffId)
            ->first() ? 1 : 0;


        $totalCount = $countFertilizerApplication + $countFieldVisit + $countHavest + $countHealthAndSafety +
            $countIntergratedPestManagement + $countLabourRight + $countLandPreparation + $countTraining + 
            $countNutrientManagement + $countPesticideApplication + $countPrePlanting +
            $countWaterIrrigation + $countWaterManagement + $countWomenEmpowerment;

        return $totalCount;
    }
    


    public function getSRPSchedule(Request $request)
    {
        $status_response = [];
        $arr_response = [];
        $startDate = Carbon::createFromFormat('d/m/Y', $request->start_date);
        $endDate = Carbon::createFromFormat('d/m/Y', $request->end_date);
        $srp_id = SRP::where('staff_id',Auth::user()->staff->id)->select('id')->get()->makeHidden(['farmer_name', 'cultivation', 'season']);
        $data = SRPSchedule::whereBetween('date_action', [$startDate,$endDate])->whereIn('srp_id',$srp_id)->get();
        foreach($data as $sub_data)
        {
            if(array_key_exists($sub_data->date_action, $status_response))
            {
                if($sub_data->is_finished == 0)
                {
                    $status_response[$sub_data->date_action] = 0;
                }
                elseif($sub_data->is_finished == 1 && $status_response[$sub_data->date_action] == 1 )
                {
                    continue;
                }
                
            }
            else
            {
               
                if($sub_data->is_finished == 0)
                {
                    $status_response[$sub_data->date_action] = 0;
                }
                elseif($sub_data->is_finished == 1)
                {
                    
                    $status_response[$sub_data->date_action] = 1;
                }
            }
        }
        foreach($status_response as $key=> $sub_status_response)
        {
            $data = [
                'date'=>$key,
                'status'=>$sub_status_response,
            ];
            array_push($arr_response,$data);
        }
        return response()->json(
            ['data'=> $arr_response
        ]);
    }
    
    public function getSRPSToday(Request $request)
    {
        $srp_id = SRP::where('staff_id',Auth::user()->staff->id)->select('id')->get()->makeHidden(['farmer_name', 'cultivation', 'season']);
        $today = Carbon::createFromFormat('d/m/Y', $request->today);
        $data = SRPSchedule::whereDate('date_action', '=',$today)->whereIn('srp_id',$srp_id)->with('srp')->get();
        return response()->json(
            [
                'data'=> $data
            ]
        );
    }

    public function getSRPSByFarmer(Request $request)
    {
        $data_schedule = SRP::where([['farmer_id',$request->farmer_id],['cultivation_id',$request->cultivation_id],['season_id',$request->season_id]])->first();
        return response()->json(
            [
                'data'=> $data_schedule->srp_schedule,
            ]
        );
    }

}
