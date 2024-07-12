<?php

namespace App\Http\Controllers;

use App\Models\Cooperative;
use App\Models\CropVariety;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AjaxOptionsController extends Controller
{
    public function getProvinces(Request $request)
    {
        $validator = Validator::make($request->all(), ['country_id' => 'required|numeric|exists:countries,id']);

        if ($validator->fails()) {
            return response()->json([]);
        }

        $provinces = Province::where('country_id', $request->input('country_id'))->get();

        return response()->json($provinces->pluck('province_name', 'id')->toArray());
    }

    public function getDistricts(Request $request)
    {
        $validator = Validator::make($request->all(), ['province_id' => 'required|numeric|exists:provinces,id']);

        if ($validator->fails()) {
            return response()->json([]);
        }

        $provinces = District::where('province_id', $request->input('province_id'))->get();

        return response()->json($provinces->pluck('district_name', 'id')->toArray());
    }

    public function getVarieties(Request $request)
    {
        $validator = Validator::make($request->all(), ['crop_information_id' => 'required|numeric|exists:crop_informations,id']);

        if ($validator->fails()) {
            return response()->json([]);
        }

        $cropVarieties = CropVariety::where('crop_id', $request->input('crop_information_id'))->get();

        return response()->json($cropVarieties->pluck('name', 'id')->toArray());
    }

    public function isEmailExist(Request $request, Cooperative $cooperative)
    {
        // allow for old cooperative
        if ($cooperative->email == $request->email) {
            return response()->json([], 200);
        }

        $emaiExist = false;
        $isExistEmailUrl = config('upstream.HEROMARKET_URL') . '/api/v2/auth/is-email-exist';
        try {
            $response = Http::withOptions(['verify' => false])->post($isExistEmailUrl, ['email' => $request->email]);

            $response = json_decode($response->getBody(), true);
            
            if ($response['email_exist']) {
                $emaiExist = true; 
            }
        } catch (\Exception $exception) {
            \Log::info($exception->getMessage());
        }

        return response()->json([], $emaiExist ? 404 : 200);
    }

    public function isPhoneExist(Request $request, Cooperative $cooperative)
    {
        // allow for old cooperative
        if ($cooperative->phone_number == $request->phone_number) {
            return response()->json([], 200);
        }

        $phoneExist = false;
        $phone_number = (int) $request->phone_number;
        $phone_number = '+84' . $phone_number;

        $phoneExistUrl = config('upstream.HEROMARKET_URL') . '/api/v2/file/is-phone-existed';

        try {
            $response = Http::withOptions(['verify' => false])->post($phoneExistUrl, ['phone_number' => $phone_number]);
            $response = json_decode($response->getBody(), true);
            if (isset($response['phone_exist']) && $response['phone_exist'] == 1) {
                $phoneExist = true;
            }
        } catch (\Exception $exception) {  
            \Log::info($exception->getMessage());
        }

        return response()->json([], $phoneExist ? 404 : 200);
    }
}
