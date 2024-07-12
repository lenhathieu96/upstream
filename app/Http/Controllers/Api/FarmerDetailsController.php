<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetCalendarRequest;
use App\Models\CropCalendar;
use App\Models\CropCalendarDetail;
use App\Models\Cultivations;
use Illuminate\Http\Request;
use App\Models\FarmerDetails;
use App\Models\FarmLand;
use App\Models\SeasonMaster;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;

class FarmerDetailsController extends Controller
{
    public function getFarmer(Request $request)
    {
        $farmer_data = FarmerDetails::with([
            'countryRelation',
            'provinceRelation',
            'districtRelation',
            'communeRelation',
            'farm_lands' => function($query) {
                $query->where('farm_lands.status', FarmLand::STATUS_ACTIVE);
            }
        ])->where('id', auth()->user()->farmer_detail?->id)->first();

        $farmer_data->farmer_photo = uploaded_asset($farmer_data->farmer_photo);
        
        $farmer_data->total_area = $farmer_data->farm_lands()->where('farm_lands.status', FarmLand::STATUS_ACTIVE)->sum('total_land_holding');

        return response()->json([
            'result' => true,
            'message' => 'Get Farmer Successfully',
            'data' =>[
                'farmer_data'=>$farmer_data
            ]
        ]);
    }

    public function getCalendarMessage(GetCalendarRequest $request)
    {
        // we only send calendar message for current season
        $currentSeasonIds = SeasonMaster::select('id')->active()->currentSeason()->pluck('id')->all();
        $cultivationIds = auth()->user()->farmer_detail?->cultivation_crop?->pluck('id')->all();

        $startDate = Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
        $endDate = Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');
        $period = CarbonPeriod::create($startDate, $endDate);
        $messages = [];

        foreach ($period as $currentDate) {
            $currentDateString = $currentDate->toDateString();
            $messages[$currentDateString] = $this->getMessages($cultivationIds, $currentSeasonIds, $currentDateString);
        }
        dd($period);
        
        return response()->json($messages);
    }

    public function getMessages($cultivationIds, $currentSeasonIds, $currentDate)
    {
        // only get unique cultivation base on: farm_land_id, season_id, crop_id
        $cultivations = Cultivations::whereIn('id', $cultivationIds)
            ->whereIn('season_id', $currentSeasonIds)
            ->whereRaw("DATE_FORMAT(STR_TO_DATE(sowing_date, '%d/%m/%Y'), '%Y-%m-%d') <= ?", [$currentDate]) 
            ->whereRaw("DATE_FORMAT(STR_TO_DATE(expect_date, '%d/%m/%Y'), '%Y-%m-%d') >= ?", [$currentDate]) 
            ->active()
            ->get()
            ->unique(function ($cultivation) {
                return $cultivation->farm_land_id . $cultivation->season_id . $cultivation->crop_id;
            })
            ->all();
 
        // get valid activity in crop_calendar_details
        $messages = [];
        foreach ($cultivations as $cultivation) {
            $cropCalendarIds = $cultivation->crops_master->crop_calendars->pluck('id')->all();
            $cropCalendarsIds = CropCalendar::whereIn('id', $cropCalendarIds)->active()->pluck('id')->all();
            $cropCalendarDetails = CropCalendarDetail::whereIn('crop_calendar_id', $cropCalendarsIds)->active()->get();
            $sowingDate = Carbon::createFromFormat('d/m/Y', $cultivation->sowing_date)->format('Y-m-d');
            
            // process with cropCalendarDetails
            foreach($cropCalendarDetails as $cropCalendarDetail) {

            }
        }
    }
}
