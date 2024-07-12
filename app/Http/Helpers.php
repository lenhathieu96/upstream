<?php

use App\Models\Commune;
use App\Models\Country;
use App\Models\CropInformation;
use App\Models\District;
use App\Models\Province;
use App\Models\SeasonMaster;
use App\Models\Uploads;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

if (!function_exists('haversineGreatCircleDistance')) {
    function haversineGreatCircleDistance($lat1, $lon1, $lat2, $lon2)
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
          }
          else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
              return ($miles * 1.609344);
          }
    }
}

//return file uploaded via uploader
if (!function_exists('uploaded_asset')) {
    function uploaded_asset($id)
    {
        if (($asset = \App\Models\Uploads::find($id)) != null) {
            return $asset->external_link == null ? my_asset($asset->file_name) : $asset->external_link;
        }
        return static_asset('assets/img/placeholder.jpg');
    }
}

if (!function_exists('my_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function my_asset($path, $secure = null)
    {
        if (env('FILESYSTEM_DRIVER') == 's3') {
            return Storage::disk('s3')->url($path);
        } else {
            return app('url')->asset($path, $secure);
        }
    }
}

if (!function_exists('static_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function static_asset($path, $secure = null)
    {
        return app('url')->asset('public/' . $path, $secure);
    }
}

// duplicates m$ excel's ceiling function
if (!function_exists('ceiling')) {
    function ceiling($number, $significance = 1)
    {
        return (is_numeric($number) && is_numeric($significance)) ? (ceil($number / $significance) * $significance) : false;
    }
}

if (!function_exists('is_email_exist_in_hero')) {
    function is_email_exist_in_hero($email)
    {
        if (empty($email)) {
            // if email is empty, we don't except email by return true
            return true;
        }

        $heromarketUrl = config('upstream.HEROMARKET_URL');
        $isExistEmailUrl = $heromarketUrl . '/api/v2/auth/is-email-exist';
        try {
            $response = Http::withOptions(['verify' => false,])->post($isExistEmailUrl, ['email' => $email]);

            $response = json_decode($response->getBody(), true);
            
            if (isset($response['email_exist'])) {
                return $response['email_exist'];
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }

        // we return default is true (not accept to modify by default)
        return true;
    }
}

if (!function_exists('is_phone_exist_in_hero')) {
    function is_phone_exist_in_hero($phoneNumber, $usePhoneFilter = true)
    {
        if (empty($phoneNumber)) {
            // if phoneNumber is empty, we don't allow phone by return true
            return true;
        }

        // Check if phone number is exist in hero market, if exist then return error
        if ($usePhoneFilter) {
            $phoneNumber = filter_phone_for_hero($phoneNumber);
        }

        $phoneExistUrl = config('upstream.HEROMARKET_URL') . '/api/v2/file/is-phone-existed';

        try {
            $response = Http::withOptions(['verify' => false])->post($phoneExistUrl, ['phone_number' => $phoneNumber]);
            $response = json_decode($response->getBody(), true);
            if (isset($response['phone_exist'])) {
                return $response['phone_exist'];
            }
        } catch (\Exception $exception) {  
            Log::error($exception->getMessage());
        }

        // we return default is true (not accept to modify by default)
        return true;
    }
}

if (!function_exists('get_farmer_in_hero')) {
    function get_farmer_in_hero($farmerId)
    {
        $getFarmertUrl = config('upstream.HEROMARKET_URL') . '/api/v2/auth/get-farmer-detail';

        try {
            $response = Http::withOptions(['verify' => false])->post($getFarmertUrl, ['upstream_farmer_id' => $farmerId]);
            Log::info('get farmer' . $response);
            $response = json_decode($response->getBody(), true);
            
            return $response;

        } catch (\Exception $exception) {  
            Log::error($exception->getMessage());
        }

        // we return if fail
        return 'call api fail';
    }
}

if (!function_exists('send_image_to_hero')) {
    function send_image_to_hero(Uploads $upload)
    {
        if (empty($upload)) {
            return null;
        }
        // send file to heromarket.vn
        $heroMarketReceiveFileUploadUrl = config('upstream.HEROMARKET_URL') . '/api/v2/file/receive-file-upload';
        $fileName = str_replace('storage/uploads/all/', '', $upload->file_name);
        $file = asset($upload->file_name);

        try {
            // send file to heromarket.vn
            if (config('upstream.HEROMARKET_URL')) {
                Log::info('send file to heromarket.vn');
                Http::withOptions(['verify' => false])
                    ->attach(
                        'upload_file', file_get_contents($file), $upload->file_name
                    )
                    ->post($heroMarketReceiveFileUploadUrl,['upload_file_name' =>$fileName]);
            }
        } catch (\Exception $exception) {  
            Log::error($exception->getMessage());
        }
    }
}

if (!function_exists('filter_phone_for_hero')) {
    function filter_phone_for_hero($phone)
    {
        $phone = str_replace('+84', '', $phone);
        $phone = '+84' . ((int) $phone);
        return $phone;
    }
}

if (!function_exists('get_country_id')) {
    function get_country_id($countryText)
    {
        $country = Country::where('country_name', 'like', '%' . $countryText . '%')->first();

        return !empty($country) ? $country->id : 1; // defaut country is vietnam, equal 1
    }
}

if (!function_exists('get_province_id')) {
    function get_province_id($provinceText)
    {
        $province = Province::where('province_name', 'like', '%' . $provinceText . '%')->first();

        return !empty($province) ? $province->id : 0; // defaut province is 0
    }
}

if (!function_exists('get_district_id')) {
    function get_district_id($districtText, $provinceId=null)
    {
        $districtQuery = District::query();
        if ($provinceId) {
            $districtQuery->where('province_id', $provinceId);
        }

        $district = $districtQuery->where('district_name', 'like', '%' . $districtText . '%')->first();

        return !empty($district) ? $district->id : 0; // defaut district is 0
    }
}

if (!function_exists('get_commune_id')) {
    function get_commune_id($communeText, $districtId=null)
    {
        $communeQuery = Commune::query();
        if ($districtId) {
            $communeQuery->where('district_id', $districtId);
        }

        $commune = $communeQuery->where('commune_name', 'like', '%' . $communeText . '%')->first();

        return !empty($commune) ? $commune->id : 0; // defaut commune is 0
    }
}

if (!function_exists('get_crop_id')) {
    function get_crop_id($cropName)
    {
        $cropInformation = CropInformation::where('name', $cropName)->first();

        if (empty($cropInformation)) {
            $cropInformation = CropInformation::where('name', 'like', '%' . $cropName . '%')->first();
        }

        return !empty($cropInformation) ? $cropInformation->id : null;
    }
}

if (!function_exists('get_season_id')) {
    function get_season_id($seasonName)
    {
        $seasonMaster = SeasonMaster::where('season_name', $seasonName)->first();

        if (empty($cropInformation)) {
            $seasonMaster = SeasonMaster::where('season_name', 'like', '%' . $seasonName . '%')->first();
        }

        return !empty($seasonMaster) ? $seasonMaster->id : null;
    }
}
