<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commune;
use App\Models\Country;
use App\Models\FarmerDetails;
use App\Models\FarmLand;
use App\Models\Staff;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function dashboard()
    {
        $staffs = Staff::withCount('farmer_details')->where('status', 'active')->get();
        $farmerCount = FarmerDetails::where('status', 'active')->count();
        $totalLandHolding = FarmLand::where('status', 'active')->sum('total_land_holding');
        $totalLandHolding = $totalLandHolding;
        $totalFarmlands = FarmLand::where('status', 'active')->count();

        return view('admin.dashboard', compact('staffs', 'farmerCount', 'totalLandHolding', 'totalFarmlands'));
    }

    public function ajaxGetStaff()
    {
        $staffs = Staff::withCount('farmer_details')->where('status', 'active')->get();

        $staffsFormat = [];
        foreach ($staffs as $staff) {
            if ($staff->farmer_details_count > 0) {
                $staffsFormat[] = [
                    $staff->name,
                    $staff->farmer_details_count,
                ];
            }
        }

        return response()->json($staffsFormat);
    }

    public function ajaxGetFarmerByCommune()
    {
        $comunessData = [];
        foreach (Commune::withCount('farmer_details')->get() as $commune) {
            if (empty($commune->farmer_details_count)) {
                continue;
            }
            $comunessData[] = [
                'name' => $commune->commune_name,
                'farmer_details_count' => $commune->farmer_details_count,
            ];
        }

        $communeSorted = collect($comunessData)->sortByDesc('farmer_details_count')->all();
        $communeResults = [];
        foreach ($communeSorted as $commune) {
            $communeResults[] = [$commune['name'], $commune['farmer_details_count']];
        }
        
        return response()->json($communeResults);
    }

    public function ajaxGetCommuneByFarmArea(Request $request) 
    {
        // Farm area by commune chart
        //$communeCategory = Commune::has('farmer_details')->pluck('commune_name')->toArray();
        $communes = Commune::has('farmer_details')->get();
        $communeByFarmAreas = [];
        if ($communes->count()) {
            foreach ($communes as $commune) {
                $farmerDetailIds = FarmerDetails::where('commune', $commune->id)->pluck('id')->toArray();
                $totalLandHoldingByCommune = FarmLand::whereIn('farmer_id', $farmerDetailIds)->sum('total_land_holding');
                $actualAreaByCommune = FarmLand::whereIn('farmer_id', $farmerDetailIds)->sum('actual_area');
                
                if (empty($totalLandHoldingByCommune)) {
                    continue;
                }

                $communeByFarmAreas[] = [
                    'name' => $commune->commune_name,
                    'total_land_holding_ha' => $totalLandHoldingByCommune,
                    'actual_area_ha' => $actualAreaByCommune,
                ];
            }
        }

        //dd($communeByFarmAreas);
        $communeByFarmAreas = collect($communeByFarmAreas)->sortByDesc('total_land_holding_ha')->all();
        $communeName = collect($communeByFarmAreas)->pluck('name');

        $communeAreaResult = [];
        $totalLandHoldingData = [];
        $actualAreaData = [];

        foreach ($communeByFarmAreas as $communeByFarmArea) {
            $totalLandHoldingData[] = $communeByFarmArea['total_land_holding_ha'];
            $actualAreaData[] = $communeByFarmArea['actual_area_ha'];
        }

        $communeAreaResult = [
            ['name' => 'Total Land Holding', 'data' => $totalLandHoldingData],
            ['name' => 'Actual Area', 'data' => $actualAreaData],
        ];

        return response()->json(['communeName' => $communeName, 'communeAreaResult' => $communeAreaResult]);

        //return view('admin.ajax_area_chart', compact('communeByFarmAreas', 'communeName'));
    }
}
