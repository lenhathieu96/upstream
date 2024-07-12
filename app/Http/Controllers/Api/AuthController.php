<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CropInformation;
use App\Models\Cultivations;
use App\Models\FarmerDetails;
use App\Models\FarmLand;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|exists:users,phone_number',
            'password' => 'required|string|min:5',
            'user_type' => 'required|string|in:super_admin,farmer,staff,boat_owner,ware_house'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => 'Login fail',
                'error' => $validator->messages(),
            ]);
        }

        $credential = [
            'phone_number' => $request->input('phone_number'),
            'password' => $request->input('password'),
            'user_type'=> $request->input('user_type'),
        ];

        if (auth()->attempt($credential)) {
            $user = User::where('phone_number',  $request->input('phone_number'))->where('user_type', $request->input('user_type'))->first();
            
            if (empty($user)) {
                $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

                return response()->json([
                    'result' => false,
                    'message' => 'Staff doesn\'t exists',
                ]);
            }

            if ($user) {
                return $this->loginSuccess($user);
            } 
        }
        
        return response()->json([
            'result' => false,
            'message' => 'The credentials did not match',
        ]);
    }

    public function loginSuccess($user, $token = null)
    {

        if (!$token) {
            $token = $user->createToken('Farm-angel API Token')->plainTextToken;
        }
        
        return response()->json([
            'result' => true,
            'message' => 'Successfully logged in',
            'data' =>[
                'user' => [
                    'id' => $user->id,
                    'type' => $user->user_type,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone_number,
                    'farmer_id' => $user?->farmer_detail?->id,
                    'staff_id' => $user?->staff?->id,
                ],
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_at' => null,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $user = request()->user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        return response()->json([
            'result' => true,
            'message' => 'Successfully logged out',
        ]);
    }

    public function dashboard(Request $request)
    {
        $request->validate([
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'nearby_km' => 'nullable|numeric',
        ]);

        if (empty(Auth::user()->staff)) {
            return response()->json([
                'result' => false,
                'message' => 'Staff is not exist',
            ]);
        }

        $farmerDetail = Auth::user()->staff?->farmer_details()->where('farmer_details.status', FarmerDetails::STATUS_ACTIVE)->take(5)->get() ?? collect();
        $totalFarmer = Auth::user()->staff?->farmer_details()->where('farmer_details.status', FarmerDetails::STATUS_ACTIVE)->count() ?? 0;
        $totalHectares = Auth::user()->staff?->farm_land_count()->where('farm_lands.status', FarmLand::STATUS_ACTIVE)->sum('total_land_holding') ?? 0;
        $totalPlot = Auth::user()->staff?->farm_land_count()->where('farm_lands.status', FarmLand::STATUS_ACTIVE)->sum('actual_area') ?? 0;
        // dd();
        $nearbyPlot = [];
        if ($request->lat && $request->lng) {
            $nearbyKm   = $request->nearby_km ?? 3;
            $farmLands = Farmland::whereHas('farmer_details', function ($query) {
                $query->where('staff_id', Auth::user()->staff->id);
            })->where('status', FarmLand::STATUS_ACTIVE)->get();
            foreach ($farmLands as $farmLand) {
                $distance = $this->distance($request->lat, $request->lng, $farmLand->lat, $farmLand->lng);
                if ($distance > $nearbyKm) {
                    continue;
                }

                array_push($nearbyPlot, $farmLand);
            }
        }

        $farmer_list = FarmerDetails::with('farm_lands:id,farm_name,actual_area,farmer_id')
            ->select('id','full_name', 'farmer_code','phone_number', 'farmer_photo')
            ->latest()
            ->take(5)
            ->get();

        $totalExpectedYield = Auth::user()->staff->farm_land->sum('est_yield');

        return response()->json([
            'result' => true,
            'message' => 'dashboard page',
            'data' => [
                'total_farmmer' => $totalFarmer,
                'total_hectares' => $totalHectares,
                'total_plot' => $totalPlot,
                'nearby_plot' => $nearbyPlot,
                'farmer_list' => $farmerDetail,
                'totalExpectedYield' => $totalExpectedYield,
            ]
        ]);
    }

    public function distance($lat1, $lon1, $lat2, $lon2) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
          return 0;
        }
        else {
          $theta = $lon1 - $lon2;
          $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
          $dist = acos($dist);
          $dist = rad2deg($dist);
          $miles = $dist * 60 * 1.1515;
      
          return ($miles * 1.609344); // return KM
        }
    }

    public function dashboardFarmer(Request $request)
    {
        if (empty(Auth::user()->farmer_detail)) {
            return response()->json([
                'result' => false,
                'message' => 'Farmer is not exist',
            ]);
        }

        $farmerId = Auth::user()->farmer_detail->id;
        $totalHectares = FarmLand::where('farmer_id', $farmerId)->active()->sum('total_land_holding') ?? 0;
        $totalPlots = FarmLand::where('farmer_id', $farmerId)->active()->count() ?? 0;
        $cultivationIds = Auth::user()->farmer_detail->cultivation_crop->pluck('id')->all();
        $estYieldQuantity = Cultivations::whereIn('id', $cultivationIds)->active()->sum('est_yield');
        $faAccount = Auth::user()->farmer_detail->faAccount;
        
        $farmLandDetailsArr = [];
        foreach (FarmLand::where('farmer_id', $farmerId)->active()->get() as $farmLand) {
            $farmLandDetailItem['farm_land_id'] = $farmLand->id;
            $farmLandDetailItem['farm_name'] = $farmLand->farm_name;
            $farmLandDetailItem['total_land_holding'] = $farmLand->total_land_holding;
            $farmLandDetailItem['total_crop'] = Cultivations::select('crop_id')->where('farm_land_id', $farmLand->id)->active()->distinct()->count();
            
            $farmLandDetailsArr[] = $farmLandDetailItem;
        }

        return response()->json([
            'result' => true,
            'message' => 'Farmer dashboard page',
            'data' => [
                'farmer_id' => $farmerId,
                'total_hectares' => $totalHectares,
                'total_plots' => $totalPlots,
                'est_yield_quantity' => $estYieldQuantity,
                'loan_ammount' => $faAccount?->loan_amount ?? 0,
                'repay_ammount' => $faAccount?->outstanding_amount ?? 0,
                'farm_lands' => $farmLandDetailsArr,
            ]
        ]);
    }
}
