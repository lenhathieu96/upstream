<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FarmerRegistrationRequest;
use App\Http\Requests\FarmerUpdateRequest;
use App\Http\Requests\GetFarmerDetailRequest;
use App\Http\Resources\FarmerResource;
use App\Models\AnimalHusbandry;
use App\Models\AssetInfo;
use App\Models\BankInfo;
use App\Models\CertificateInformation;
use App\Models\Commune;
use App\Models\Country;
use App\Models\CropInformation;
use App\Models\District;
use App\Models\FamilyInfo;
use App\Models\FarmCatalogue;
use App\Models\FarmEquipment;
use App\Models\FarmerCountable;
use App\Models\FarmerDetails;
use App\Models\FarmLand;
use App\Models\FinanceInfo;
use App\Models\InsuranceInfo;
use App\Models\Province;
use App\Models\Uploads;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FarmersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // private $log_actitvities;
    // public function __construct($log_actitvities)
    // {
    //     $this->;
    // }

    public function index(Request $request)
    {
        $user_login = Auth::user();
        if(!($user_login->staff))
        {
            return response()->json([
                'result' => true,
                'message' => 'Staff Not Exist Try Again',
            ]);
        }

        $staffId = $user_login->staff->id;
        $search = $request->get('search', null);
        
        $farmerDetailQuery = FarmerDetails::with([
            'countryRelation',
            'provinceRelation',
            'districtRelation',
            'communeRelation',
        ])
            ->withCount(['farm_lands' => function($query) {
                $query->where('farm_lands.status', FarmLand::STATUS_ACTIVE);
            }])
            ->where('status', FarmerDetails::STATUS_ACTIVE)
            ->where('staff_id', $staffId)
            ->orderByDesc('id');

        if (!empty($search)) {
            $farmerDetailQuery->where("full_name", 'like', '%'.$search.'%')
                ->orWhere("phone_number",$search)
                ->orWhere("farmer_code",$search);
        }

        // filter for distribution input (Get All)
        $filterToGetAll = [
            'cooperative_id',
            'province',
            'commune',
        ];

        foreach ($filterToGetAll as $column) {
            if ($request->input($column)) {
                $farmerDetailQuery->where($column, $request->input($column));
            }
        }

        if ($request->input('whereHasCultivation') === 1) {
            $farmerDetailQuery->whereHas('cultivation_crop');
        }

        if ($request->filled($filterToGetAll) && $farmerDetailQuery->exists()) {
            return $this->success(['farmer_data' => FarmerResource::collection($farmerDetailQuery->get())]);
        }

        $data = $farmerDetailQuery->paginate(
            $request->input('per_page', 15)
        );
        
        return response()->json([
            'result' => true,
            'message' => 'Get All Farmer Successfully',
            'data' =>[
                'farmer_data'=> $data
            ]
        ]);
    }

    public function getSearchFarmer($keyword=null)
    {
        $farmerQuery = FarmerDetails::select('id', 'farmer_code', 'full_name')->where('status', FarmerDetails::STATUS_ACTIVE)->where('staff_id', auth()->user()?->staff?->id);

        if ($keyword) {
            $farmerQuery->where("full_name", 'like', '%' . $keyword . '%')
                ->orWhere("farmer_code", 'like', '%' . $keyword. '%');
        }

        $farmers = $farmerQuery->get()->makeHidden(['avatar_url', 'id_proof_photo_url', 'full_address', 'thumbnail']);

        return response()->json([
            'result' => true,
            'message' => 'Search Farmer Successfully',
            'data' =>[
                'farmer_data'=> $farmers
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function drop_down_for_register()
    {
        $data_enrollment_place = [];
        $data_identity_proof = [];
        $data_gender = [];
        $identity_proof = FarmCatalogue::where('NAME','Id Proof')->first();
        if(isset($identity_proof))
        {
            $data_identity_proof = $identity_proof->catalogue_value()->get();
        }
        $enrollment_place = FarmCatalogue::where('NAME','Enrollment Place')->first();
        if(isset($enrollment_place))
        {
            $data_enrollment_place = $enrollment_place->catalogue_value()->get();
        }
        $gender = FarmCatalogue::where('NAME','Gender')->first();
        if(isset($gender))
        {
            $data_gender = $gender->catalogue_value()->get();
        }

        return response()->json([
            'result' => true,
            'message' => 'Farmer Created Successfully',
            'data'=>[
                'data_identity_proof' =>$data_identity_proof,
                'data_enrollment_place' =>$data_enrollment_place,
                'data_gender' =>$data_gender,
                'cooperatives' => auth()->user()->staff->cooperatives,
            ]
            
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(GetFarmerDetailRequest $request)
    {
        $staff = Auth::user()->staff;
        $farmer_data = FarmerDetails::with([
            'countryRelation',
            'provinceRelation',
            'districtRelation',
            'communeRelation',
            'farm_lands' => function($query) {
                $query->where('farm_lands.status', FarmLand::STATUS_ACTIVE);
            }
        ])->find($request->route('id'));

        if ($request->input('whereHasCultivation') === 1 && empty($farmer_data->cultivation_crop)) {
            $this->fail('This Farmer Does Not Have Any Cultivation');
        }

        $farmer_data->farmer_photo = uploaded_asset($farmer_data->farmer_photo);
        
        $farmer_data->total_area = $farmer_data->farm_lands()->where('farm_lands.status', FarmLand::STATUS_ACTIVE)->sum('total_land_holding');

        if($staff->id == $farmer_data->staff_id)
        {
            return response()->json([
                'result' => true,
                'message' => 'Get Farmer Successfully',
                'data' =>[
                    'farmer_data'=>$farmer_data
                ]
            ]);
        }
        else
        {
            return $this->fail('This farmer is not belongs to you!');
        }
    }



    /**
     * Update the specified resource in storage.
     */
    // Personal Infomartion
    public function update_personal_info(FarmerUpdateRequest $request)
    {
        $data_log_activities = [];
        $data_log_activities['action'] = 'update';
        $data_log_activities['lat'] = $request->staff_lat;
        $data_log_activities['lng'] = $request->staff_lng;
        $data_log_activities['request'] = $request->all();
        
        $farmer_details = FarmerDetails::find($request->farmer_id);
        
        if (!$farmer_details) {
            return response()->json([
                'result' => false,
                'message' =>"Farmer Not Exists",
            ]);
        }

        $staff = Auth::user()->staff;
        if ($farmer_details->phone_number != $request->phone_number) {
            $phone = (int) $request->phone_number;
            $farmerByPhone1 = FarmerDetails::where('phone_number', $phone)->first();
            $farmerByPhone2 = FarmerDetails::where('phone_number', '0' . $phone)->first();
            $userByPhone1 = User::where('phone_number', $phone)->first();
            $userByPhone2 = User::where('phone_number', '0' . $phone)->first();

            // check if phone is exist in upstream, if exist then return error
            if ($farmerByPhone1 || $farmerByPhone2 || $userByPhone1 || $userByPhone2) {
                return response()->json([
                    'result' => false,
                    'message' => 'Farmer phone already exists',
                ]);
            }

            // Check if phone number is exist in hero market, if exist then return error
            if (is_phone_exist_in_hero($request->phone_number)) {
                return response()->json([
                    'result' => false,
                    'message' => 'Heromarket phone already exists',
                ]);
            }
        }

        $email = "";
        if($request->email != "")
        {
            $email = $request->email;
        }

        $password = "";
        if($request->password != "")
        {
            $password = Hash::make($request->password); 
        }

        $farmer_photo = [];
        if (!empty($request->all()['farmer_photo'])) {
            foreach ($request->all()['farmer_photo'] as $photo) {                        
                $id = (new UploadsController)->upload_photo($photo,$request->farmer_id, 'farmer');

                if (!empty($id)) {
                    array_push($farmer_photo, $id);
                }
            }    
        }

        $id_proof_photo = [];
        if (!empty($request->all()['id_proof_photo'])) {
            
            foreach ($request->all()['id_proof_photo'] as $photo) {                        
                $id = (new UploadsController)->upload_photo($photo,$request->farmer_id, 'farmer');

                if (!empty($id)) {
                    array_push($id_proof_photo, $id);
                }
            }    
        }

        $data_farmer_details =[
            'cooperative_id' => $request->cooperative_id ?? $farmer_details->cooperative_id,
            'staff_id'=>$farmer_details->staff_id,
            'user_id'=>$farmer_details->user_id,
            'enrollment_date' =>$farmer_details->enrollment_date,
            'enrollment_place'=>$farmer_details->enrollment_place,
            'full_name'=>$request->full_name ?? $farmer_details->full_name,
            'phone_number'=> $request->phone_number ?? $farmer_details->phone_number,
            'identity_proof'=>$request->identity_proof ?? $farmer_details->identity_proof,
            'country'=>$request->country  ?? $farmer_details->country,
            'province'=>$request->province  ?? $farmer_details->province,
            'district'=>$request->district  ?? $farmer_details->district,
            'commune'=>$request->commune  ?? $farmer_details->commune,
            'village'=>$request->village  ?? $farmer_details->village,
            'lng'=>$request->lng  ?? $farmer_details->lng,
            'lat'=>$request->lat  ?? $farmer_details->lat,
            'proof_no'=>$request->proof_no  ?? $farmer_details->proof_no,
            'gender'=>$request->gender  ?? $farmer_details->gender,
            'farmer_code'=>$farmer_details->farmer_code,
            'dob'=>$request->dob ?? $farmer_details->dob,
            'is_online'=>$request->is_online ?? $farmer_details->is_online,
            'srp_certification'=> !empty($request->srp_certification) ?  $request->srp_certification : $farmer_details->srp_certification,
            'farmer_photo'=>!empty($farmer_photo) ? implode(',', $farmer_photo) : $farmer_details->farmer_photo,
            'id_proof_photo'=> !empty($id_proof_photo) ? implode(',', $id_proof_photo) : $farmer_details->id_proof_photo,
        ];
       
        try {
            $farmer_data = $farmer_details->update($data_farmer_details);
            $data_log_activities['status_code'] = 200;
            $data_log_activities['status_msg'] = 'Farmer Update Successfully';
            $this->create_log((object) $data_log_activities);

            $farmer_details->refresh();
            $this->createOrUpdateUserInHeromarket($farmer_details);

            return response()->json([
                'result' => true,
                'message' => 'Farmer Update Successfully',
                'data' =>FarmerDetails::find($request->farmer_id)
            ]);
        } catch (\Exception $e) {  
            $data_log_activities['status_code'] = 400;
            $data_log_activities['status_msg'] = $e->getMessage();
            $this->create_log((object) $data_log_activities);
            return response()->json([
                'result' => true,
                'message' => 'Farmer Update Failed',
            ]);
        }
    }

    public function createOrUpdateUserInHeromarket(FarmerDetails $farmerDetail)
    {
        $farmerFromHero = get_farmer_in_hero($farmerDetail->id);

        if ($farmerFromHero === 'call api fail') {
            Log::info('call api fail');
            return null;
        }

        $phoneNumber = filter_phone_for_hero($farmerDetail->phone_number);

        if ($farmerFromHero['farmer_exist']) {
            // update farmer detail
            if ($farmerFromHero['phone'] == $phoneNumber) {
                Log::info('phone is the same, no need to update');
                return null;
            }

            if (is_phone_exist_in_hero($phoneNumber)) {
                Log::info('phone is already exists in hero, cannot update');
                return null;
            }

            try {
                $updateFarmerUrl = config('upstream.HEROMARKET_URL') . '/api/v2/auth/update-farmer';
                $response = Http::withOptions(['verify' => false])->post($updateFarmerUrl, ['upstream_farmer_id' => $farmerDetail->id, 'phone' => $phoneNumber]);
                Log::info('update farmer phone successfully');
                $response = json_decode($response->getBody(), true);
            } catch (Exception $exception) {
                Log::info('update farmer fail: ' . $exception->getMessage());
            }
            
            return null;
        } 


        // create farmer detail
        $signupApiUrl = env('HEROMARKET_URL') . '/api/v2/auth/signup';
        $email = 'upstream_' . uniqid() . '@gmail.com';

        $dataRegisterSeller = [
            'create_seller_from_upstream' => 1,
            'bussiness_name' => $farmerDetail->full_name,
            'email' => $email,
            'password' => '12345678',   
            'password_confirmation' => '12345678',   
            'country_code' => '84',
            'phone' => $phoneNumber,
            'country' => 238,
            'city' => 48358,
            'state' => 4056,
            'address' => $farmerDetail->short_address,
            'user_type' => 'farmer',
            'lat' => $farmerDetail->lat,
            'lng' => $farmerDetail->lng,
            'is_enterprise' => 0,
            'categories_id' => 1,
            'upstream_farmer_id' => $farmerDetail->id,
        ];

        $upload = $farmerDetail->thumbnail;
        if (!empty($upload)) {
            $dataRegisterSeller['upload_from_upstream'] = 1;
            $dataRegisterSeller['file_original_name'] = $upload->file_original_name;
            $dataRegisterSeller['file_name'] = str_replace('storage/', '', $upload->file_name);
            $dataRegisterSeller['file_size'] = $upload->file_size;
            $dataRegisterSeller['extension'] = $upload->extension;
            $dataRegisterSeller['type'] = $upload->type;
        }

        try {
            // register
            $response = Http::withOptions(['verify' => false])->post($signupApiUrl, $dataRegisterSeller);
            $response = json_decode($response->getBody(), true);
            
            // send image file
            if (!empty($upload)) {
                send_image_to_hero($upload);
            }
            
            Log::info('register farmer successfully: ' . $farmerDetail->full_name);

        } catch (Exception $exception) {
            Log::info('register farmer fail: ' . $exception->getMessage());
        }
    }

    // Family Info
    public function update_family_info(Request $request, string $id)
    {
        $data_log_activities = [];
        $data_log_activities['action'] = 'edit';
        $data_log_activities['lat'] = $request->staff_lat;
        $data_log_activities['lng'] = $request->staff_lng;
        $data_log_activities['request'] = $request->all();
        $data_family = $request->data_family;
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            $data_log_activities['status_code'] = 404;
            $data_log_activities['status_msg'] = 'Farmer Not Exists';
            $this->create_log((object) $data_log_activities);
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        // $family_info = new FamilyInfo();
        $data_family_info = [
            'education'=>$data_family['education'],
            'marial_status'=>$data_family['marial_status'],
            'parent_name'=>$data_family['parent_name'],
            'spouse_name'=>$data_family['spouse_name'],
            'no_of_family'=>$data_family['no_of_family'],
            'total_child_under_18'=>json_encode($data_family['total_child_under_18']),
            'total_child_under_18_going_school'=>$data_family['total_child_under_18_going_school']
        ];
        try {
            $family_info = FamilyInfo::updateOrCreate(['farmer_id'=>$farmer_data->id], $data_family_info );
            if(isset($family_info))
            {
                $data_log_activities['status_code'] = 200;
                $data_log_activities['status_msg'] = 'Update Family Information Successfully';
                $this->create_log((object) $data_log_activities);
                return response()->json([
                    'result' => true,
                    'message' => 'Update Family Information Successfully',
                    'data'=>[
                        'family_info'=>$family_info
                    ]
                    
                ]);
            }
        } 
        catch (\Exception $e) {  
            $data_log_activities['status_code'] = 400;
            $data_log_activities['status_msg'] = $e->getMessage();
            $this->create_log((object) $data_log_activities);
            return response()->json([
                'result' => true,
                'message' => 'Update Family Information Failed',
            ]);
        }
    }

    // Asset Info
    public function update_asset_info(Request $request, string $id)
    {
        $data_log_activities = [];
        $data_log_activities['action'] = 'edit';
        $data_log_activities['lat'] = $request->staff_lat;
        $data_log_activities['lng'] = $request->staff_lng;
        $data_log_activities['request'] = $request->all();
        $data_asset = $request->data_asset;
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            $data_log_activities['status_code'] = 404;
            $data_log_activities['status_msg'] = 'Farmer Not Exists';
            $this->create_log((object) $data_log_activities);
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        // $family_info = new FamilyInfo();
        $data_asset_info = [
            'housing_ownership'=>$data_asset['housing_ownership'],
            'house_type'=>$data_asset['house_type'],
            'consumer_electronic'=>$data_asset['consumer_electronic'],
            'vehicle'=>$data_asset['vehicle']
        ];
        try {
            $asset_info = AssetInfo::updateOrCreate(['farmer_id'=>$farmer_data->id], $data_asset_info );
            if(isset($asset_info))
            {
                $data_log_activities['status_code'] = 200;
                $data_log_activities['status_msg'] = 'Update Asset Information Successfully';
                $this->create_log((object) $data_log_activities);
                return response()->json([
                    'result' => true,
                    'message' => 'Update Asset Information Successfully',
                    'data'=>[
                        'asset_info'=>$asset_info
                    ]
                    
                ]);
            }
        } 
        catch (\Exception $e) { 
            $data_log_activities['status_code'] = 400;
            $data_log_activities['status_msg'] = $e->getMessage();
            $this->create_log((object) $data_log_activities);
            return response()->json([
                'result' => false,
                'message' => 'Update Asset Information Failed',
            ]);
        }
    }

    //Bank Info 
    public function update_bank_info(Request $request, string $id)
    {
        $data_bank = $request->data_bank;
        $farmer_data = FarmerDetails::find($id);
        $all_bank_update = [];
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        foreach($data_bank as $sub_data_bank)
        {
            $data_bank_info = [
                'farmer_id'=>$farmer_data->id,
                'accout_type'=>$sub_data_bank['accout_type'],
                'accout_no'=>$sub_data_bank['accout_no'],
                'bank_name'=>$sub_data_bank['bank_name'],
                'branch_details'=>$sub_data_bank['branch_details'],
                'sort_code'=>$sub_data_bank['sort_code']
            ];
            $bank_info = BankInfo::updateOrCreate(['id'=>$sub_data_bank['id']], $data_bank_info );
            array_push($all_bank_update,$bank_info);  
        }
        
        
        if(count($all_bank_update)>0)
        {
            return response()->json([
                'result' => true,
                'message' => 'Update Bank Information Successfully',
                'data'=>[
                    'all_bank_update'=>$all_bank_update
                ]
                
            ]);
        }
        else
        {
            return response()->json([
                'result' => false,
                'message' => 'Update Insurance Information Failed',
                'data'=>[
                ]
            ]);
        }
    }

    // Insurance Info
    public function update_insurance_info(Request $request, string $id)
    {
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        $all_insurance_update = [];
        if(count($request->data_insurance) == 0)
        {
            return response()->json([
                'result' => false,
                'message' => 'No Data Update',
            ]);
        }
        foreach($request->data_insurance as $sub_data_insurance)
        {
            $life_insurance = "";
            $provider_life_insurance = "";
            $life_insurance_amount = 0;
            $life_insurance_enrolled_date = "";
            $life_insurance_end_date = "";
            $health_insurance = "";
            $provider_health_insurance = "";
            $health_insurance_amount = 0;
            $health_insurance_enrolled_date = "";
            $health_insurance_end_date = "";
            $crop_insurance = "";
            $provider_crop_insurance = "";
            $crop_insured = 0;
            $no_of_area_insured = 0;
            $crop_insurance_enrolled_date = "";
            $crop_insurance_end_date = "";
            $social_insurance = "";
            $provider_social_insurance = "";
            $social_insurance_enrolled_date = "";
            $social_insurance_end_date = "";
            $other_insurance = $request->other_insurance;
            if($sub_data_insurance['life_insurance'] == "yes")
            {
                $life_insurance = $sub_data_insurance['life_insurance'];
                $provider_life_insurance = $sub_data_insurance['provider_life_insurance'];
                $life_insurance_amount = $sub_data_insurance['life_insurance_amount'];
                $life_insurance_enrolled_date = $sub_data_insurance['life_insurance_enrolled_date'];
                $life_insurance_end_date = $sub_data_insurance['life_insurance_end_date'];
            }
            if($sub_data_insurance['health_insurance'] == "yes")
            {
                $health_insurance = $sub_data_insurance['health_insurance'];
                $provider_health_insurance = $sub_data_insurance['provider_health_insurance'];
                $health_insurance_amount = $sub_data_insurance['health_insurance_amount'];
                $health_insurance_enrolled_date = $sub_data_insurance['health_insurance_enrolled_date'];
                $health_insurance_end_date = $sub_data_insurance['health_insurance_end_date'];
            }
            if($sub_data_insurance['crop_insurance'] == "yes")
            {
                $crop_insurance = $sub_data_insurance['crop_insurance'];
                $provider_crop_insurance = $sub_data_insurance['provider_crop_insurance'];
                $crop_insured = $sub_data_insurance['crop_insured'];
                $no_of_area_insured = $sub_data_insurance['no_of_area_insured'];
                $crop_insurance_enrolled_date = $sub_data_insurance['crop_insurance_enrolled_date'];
                $crop_insurance_end_date = $sub_data_insurance['crop_insurance_end_date'];
            }
            if($sub_data_insurance['social_insurance'] == "yes")
            {
                $social_insurance = $sub_data_insurance['social_insurance'];
                $provider_social_insurance = $sub_data_insurance['provider_social_insurance'];
                $social_insurance_enrolled_date = $sub_data_insurance['social_insurance_enrolled_date'];
                $life_insurance_enrolled_date = $sub_data_insurance['life_insurance_enrolled_date'];
                $social_insurance_end_date = $sub_data_insurance['social_insurance_end_date'];
            }
            $data_insurance_info = [
                'farmer_id'=>$farmer_data->id,
                'life_insurance'=>$life_insurance,
                'provider_life_insurance'=>$provider_life_insurance,
                'life_insurance_amount'=>$life_insurance_amount,
                'life_insurance_enrolled_date'=>$life_insurance_enrolled_date,
                'life_insurance_end_date'=>$life_insurance_end_date,
                'health_insurance'=>$health_insurance,
                'provider_health_insurance'=>$provider_health_insurance,
                'health_insurance_amount'=>$health_insurance_amount,
                'health_insurance_enrolled_date'=>$health_insurance_enrolled_date,
                'health_insurance_end_date'=>$health_insurance_end_date,
                'crop_insurance'=>$crop_insurance,
                'provider_crop_insurance'=>$provider_crop_insurance,
                'crop_insured'=>$crop_insured,
                'no_of_area_insured'=>$no_of_area_insured,
                'crop_insurance_enrolled_date'=>$crop_insurance_enrolled_date,
                'crop_insurance_end_date'=>$crop_insurance_end_date,
                'social_insurance'=>$social_insurance,
                'provider_social_insurance'=>$provider_social_insurance,
                'social_insurance_enrolled_date'=>$social_insurance_enrolled_date,
                'social_insurance_end_date'=>$social_insurance_end_date,
                'other_insurance'=>$other_insurance
            ];
            // if($sub_data_insurance['id'] == "")
            // {
            //     $creat_insurance = new InsuranceInfo();
            //     $insurance_info = $creat_insurance->create($data_insurance_info);
            //     array_push($all_insurance_update,$insurance_info);  
            // }
            // else
            // {
            //     $creat_insurance = InsuranceInfo::find($sub_data_insurance['id']);
            //     $insurance_info = $creat_insurance->create($data_insurance_info);
            //     array_push($all_insurance_update,$insurance_info);  
            // }
            $insurance_info = InsuranceInfo::updateOrCreate(['id'=>$sub_data_insurance['id']], $data_insurance_info );
            array_push($all_insurance_update,$insurance_info);  
        }
        if(count($all_insurance_update)>0)
        {
            return response()->json([
                'result' => true,
                'message' => 'Update Insurance Information Successfully',
                'data'=>[
                    'all_insurance_update'=>$all_insurance_update
                ]
                
            ]);
        }
        else
        {
            return response()->json([
                'result' => false,
                'message' => 'Update Insurance Information Failed',
                'data'=>[
                ]
            ]);
        }
    }

    // Finance Info
    public function update_finance_info(Request $request, string $id)
    {
        $data_finance = $request->data_finance;
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        $data_finance_info = [
            'loan_taken_last_year'=>$data_finance['loan_taken_last_year'],
            'loan_taken_from'=>$data_finance['loan_taken_from'],
            'loan_amount'=>$data_finance['loan_amount'],
            'purpose'=>$data_finance['purpose'],
            'loan_interest'=>$data_finance['loan_interest'],
            'interest_period'=>$data_finance['interest_period'],
            'security'=>$data_finance['security'],
            'loan_repayment_amount'=>$data_finance['loan_repayment_amount'],
            'loan_repayment_date'=>$data_finance['loan_repayment_date']
        ];
        $finance_info = FinanceInfo::updateOrCreate(['farmer_id'=>$farmer_data->id], $data_finance_info );
        if(isset($finance_info))
        {
            return response()->json([
                'result' => true,
                'message' => 'Update Finance Information Successfully',
                'data'=>[
                    'finance_info'=>$finance_info
                ]
                
            ]);
        }
        else
        {
            return response()->json([
                'result' => false,
                'message' => 'Update Finance Information Failed',
            ]);
        }

    }

    // Farm Equipment 
    public function update_farm_equipment(Request $request, string $id)
    {
        $data_farm_equipment = $request->data_farm_equipment;
        $farmer_data = FarmerDetails::find($id);
        $all_farm_equipment = [];
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        foreach($data_farm_equipment as $sub_data_farm_equipment)
        {
            $data_create_farm_equipment = [
                'farmer_id'=>$farmer_data->id,
                'farm_equipment_items'=>$sub_data_farm_equipment['farm_equipment_items'],
                'farm_equipment_items_count'=>$sub_data_farm_equipment['farm_equipment_items_count'],
                'year_of_manufacture'=>$sub_data_farm_equipment['year_of_manufacture'],
                'year_of_purchase'=>$sub_data_farm_equipment['year_of_purchase']
            ];
            $farm_equipment = FarmEquipment::updateOrCreate(['id'=>$sub_data_farm_equipment['id']], $data_create_farm_equipment);
            array_push($all_farm_equipment,$farm_equipment);  
        }
        
        
        if(count($all_farm_equipment)>0)
        {
            return response()->json([
                'result' => true,
                'message' => 'Update Farm Equipment Successfully',
                'data'=>[
                    'all_farm_equipment'=>$all_farm_equipment
                ]
                
            ]);
        }
        else
        {
            return response()->json([
                'result' => false,
                'message' => 'Update Farm Equipment Failed',
                'data'=>[
                ]
            ]);
        }
    }

    // Animal Husbandry
    public function update_animal_husbandry(Request $request, string $id)
    {
        $data_animal_husbandry = $request->data_animal_husbandry;
        $farmer_data = FarmerDetails::find($id);
        $all_animal_husbandry = [];
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        foreach($data_animal_husbandry as $sub_data_animal_husbandry)
        {
            $data_create_animal_husbandry = [
                'farmer_id'=>$farmer_data->id,
                'farm_animal'=>$sub_data_animal_husbandry['farm_animal'],
                'animal_count'=>$sub_data_animal_husbandry['animal_count'],
                'fodder'=>$sub_data_animal_husbandry['fodder'],
                'animal_housing'=>$sub_data_animal_husbandry['animal_housing'],
                'revenue'=>$sub_data_animal_husbandry['revenue'],
                'breed_name'=>$sub_data_animal_husbandry['breed_name'],
                'animal_for_growth'=>$sub_data_animal_husbandry['animal_for_growth']
            ];
            $animal_husbandry = AnimalHusbandry::updateOrCreate(['id'=>$sub_data_animal_husbandry['id']], $data_create_animal_husbandry);
            array_push($all_animal_husbandry,$animal_husbandry);  
        }
        
        
        if(count($all_animal_husbandry)>0)
        {
            return response()->json([
                'result' => true,
                'message' => 'Update Animal Husbandry Successfully',
                'data'=>[
                    'all_animal_husbandry'=>$all_animal_husbandry
                ]
                
            ]);
        }
        else
        {
            return response()->json([
                'result' => false,
                'message' => 'Update Animal Husbandry Failed',
                'data'=>[
                ]
            ]);
        }
    }

    // Certification
    public function update_certificate(Request $request, string $id)
    {
        $data_certificate = $request->data_certificate;
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        // $family_info = new FamilyInfo();
        $data_certitifcate_info = [
            'is_certified_farmer'=>$data_certificate['is_certified_farmer'],
            'certification_type'=>$data_certificate['certification_type'],
            'year_of_ics'=>$data_certificate['year_of_ics']
        ];
        $certitifcate_info = CertificateInformation::updateOrCreate(['farmer_id'=>$farmer_data->id], $data_certitifcate_info );
        if(isset($certitifcate_info))
        {
            return response()->json([
                'result' => true,
                'message' => 'Update Certificate Information Successfully',
                'data'=>[
                    'certitifcate_info'=>$certitifcate_info
                ]
                
            ]);
        }
        else
        {
            return response()->json([
                'result' => false,
                'message' => 'Update Family Information Failed',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
    public function registration(FarmerRegistrationRequest $request)
    {
        $data_log_activities = [];
        $data_log_activities['action'] = 'create';
        $data_log_activities['lat'] = $request->staff_lat;
        $data_log_activities['lng'] = $request->staff_lng;
        $data_log_activities['request'] = $request->all();
        $staff = Auth::user()->staff;
        
        if (!$staff) {
            return response()->json([
                'result' => false,
                'message' => "staff doesn't exist.",
            ]);
        }

        $phone = (int) $request->phone_number;
        $farmerByPhone1 = FarmerDetails::where('phone_number', $phone)->first();
        $farmerByPhone2 = FarmerDetails::where('phone_number', '0' . $phone)->first();
        $userByPhone1 = User::where('phone_number', $phone)->first();
        $userByPhone2 = User::where('phone_number', '0' . $phone)->first();

        // check if phone is exist in upstream, if exist then return error
        if ($farmerByPhone1 || $farmerByPhone2 || $userByPhone1 || $userByPhone2) {
            return response()->json([
                'result' => false,
                'message' => 'Farmer phone already exists',
            ]);
        }

        // Check if phone number is exist in hero market, if exist then return error
        if (is_phone_exist_in_hero($request->phone_number)) {
            return response()->json([
                'result' => false,
                'message' => 'Heromarket phone already exists',
            ]);
        }
        
        $countable = FarmerCountable::find(1);
        $farmer_code = 'FA'.date('Y').date('m').date('d').$countable->count_number;
        
        $data_farmer_details =[
            'staff_id'=>$staff->id,
            'user_id'=>0,
            'enrollment_date' =>$request->enrollment_date,
            'enrollment_place'=>$request->enrollment_place,
            'full_name'=>$request->full_name,
            'phone_number'=>$request->phone_number,
            'identity_proof'=>$request->identity_proof,
            'country'=>$request->country,
            'province'=>$request->province,
            'district'=>$request->district,
            'commune'=>$request->commune,
            'village'=>$request->village,
            'lng'=>$request->lng,
            'lat'=>$request->lat,
            'proof_no'=>$request->proof_no,
            'gender'=>$request->gender,
            'farmer_code'=>$farmer_code,
            'dob'=>$request->dob,
            'is_online'=>$request->is_online,
            'srp_certification'=>$request->srp_certification,
            'cooperative_id' => $request->cooperative_id
            // 'farmer_photo'=>implode(',', $farmer_photo),
            // 'id_proof_photo'=>implode(',', $id_proof_photo),
        ];
       
        try {
            $farmerDetail = FarmerDetails::create($data_farmer_details);

            if ($farmerDetail) {
                $userId = $this->createUserFromFarmer($farmerDetail);
                $farmerDetail->user_id = $userId;
                $farmerDetail->save();

                $farmerDetail->faAccount()->create([
                    'typee' => 3,
                    'acc_type' => 'FRA'
                ]);
            }

            $farmer_photo = [];
            if (!empty($request->all()['farmer_photo'])) {
                foreach ($request->all()['farmer_photo'] as $photo) {                        
                    $id = (new UploadsController)->upload_photo($photo,$farmerDetail->id, 'farmer');

                    if (!empty($id)) {
                        array_push($farmer_photo, $id);
                    }
                }    
            }

            $id_proof_photo = [];
            if (!empty($request->all()['id_proof_photo'])) {
                
                foreach ($request->all()['id_proof_photo'] as $photo) {                        
                    $id = (new UploadsController)->upload_photo($photo,$farmerDetail->id, 'farmer');

                    if (!empty($id)) {
                        array_push($id_proof_photo, $id);
                    }
                }    
            }

            if (!empty($farmer_photo)) {
                $farmerDetail->farmer_photo = implode(',', $farmer_photo);
                $farmerDetail->save();
            }

            if (!empty($id_proof_photo)) {
                $farmerDetail->id_proof_photo = implode(',', $id_proof_photo);
                $farmerDetail->save();
            }

            $data_log_activities['status_code'] = 200;
            $data_log_activities['status_msg'] = 'Farmer Registration Successfully';
            $this->create_log((object) $data_log_activities);
            $countable->update(['count_number'=>$countable->count_number +=1]);
            

            // save farmer to heromarket.vn
            $email = "";
            if (!is_email_exist_in_hero($request->email)) {
                $email = $request->email;
            }

            if (empty($email)) {
                $email = 'upstream_' . uniqid() . '@gmail.com';
            }

            $signupApiUrl = config('upstream.HEROMARKET_URL') . '/api/v2/auth/signup';
            $phone_number = (int) $request->phone_number;
            $phone_number = '+84' . $phone_number;
            
            $response = null;
            // farmer has role seller in heromarket
            $dataRegisterSeller = [
                'create_seller_from_upstream' => 1,
                'bussiness_name' => $request->full_name,
                'email' => $email,
                'password' => '12345678',   
                'password_confirmation' => '12345678',   
                'country_code' => '84',
                'phone' => $phone_number,
                'country' => 238,
                'city' => 48358,
                'state' => 4056,
                'address' => 'Vietnam, Long An city, Long An',
                'user_type' => 'farmer',
                'lat' => $request->lat,
                'lng' => $request->lng,
                'is_enterprise' => 0,
                'categories_id' => 1,
                'upstream_farmer_id' => $farmerDetail->id,
            ];

            $farmerDetail->refresh();
            $upload = $farmerDetail->thumbnail;

            if (!empty($upload)) {
                $dataRegisterSeller['upload_from_upstream'] = 1;
                $dataRegisterSeller['file_original_name'] = $upload->file_original_name;
                $dataRegisterSeller['file_name'] = str_replace('storage/', '', $upload->file_name);
                $dataRegisterSeller['file_size'] = $upload->file_size;
                $dataRegisterSeller['extension'] = $upload->extension;
                $dataRegisterSeller['type'] = $upload->type;
            }

            $response = Http::withOptions(['verify' => false])->post($signupApiUrl, $dataRegisterSeller);
            
            // return immediately if cannot register seller
            if ($response?->getStatusCode() != 200) {
                \Log::error('Cannot register seller' .  $response?->body());
                $data_log_activities['status_code'] = 400;
                $data_log_activities['status_msg'] = 'Cannot register seller' . $response?->body();
                $this->create_log((object) $data_log_activities);
            } else {
                $response = json_decode($response->getBody(), true);
                if (isset($response['result']) && $response['result'] && !empty($upload)) {
                    // send file to heromarket.vn 
                    if (!empty($upload)) {
                        send_image_to_hero($upload);
                    }
                }
            }


            return response()->json([
                'result' => true,
                'message' => 'Farmer Registration Successfully',
                'data' =>[
                    'farmer_data' => $farmerDetail
                ]
            ]);
        } catch (\Exception $e) {  
            $data_log_activities['status_code'] = 400;
            $data_log_activities['status_msg'] = $e->getMessage();
            $this->create_log((object) $data_log_activities);
            // $log_actitvities->store_log((object) $data_log_activities);
            
            return response()->json([
                'result' => true,
                'message' => 'Farmer Registration Failed',
            ]);
        }
        
    }

    private function createUserFromFarmer($farmerDetail)
    {
        $username = Str::slug($farmerDetail->full_name, '');
        while(true) {
            if (!$this->isExistUsername($username)) {
                break;
            }
            $username = Str::slug($farmerDetail->full_name, '') . rand(10,999);
        }

        $user = User::create([
            'name' => $farmerDetail->full_name,
            'user_type' => 'farmer',
            'username' => $username,
            'email' => null,
            'password' => Hash::make('12345678'),
            'phone_number' => $farmerDetail->phone_number,
            'email_verified_at' => null,
            'banned' => 0,
        ]);

        return $user->id;
    }

    private function isExistUsername($username)
    {
        return User::where('username', $username)->exists();
    }

    public function get_data_for_family_info($id)
    {
        if(!isset($id))
        {
            return response()->json([
                'result' => false,
                'message' => 'Missing Parameter',
            ]);
        }
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        if ($farmer_data->family_info ) {
            $family_info = $farmer_data->family_info; 
            $family_info->total_child_under_18 = json_decode($family_info->total_child_under_18); 
        } else {
            $family_info = null;
        }
       
        
        $data_education = [];
        $data_marial_status = [];
        $data_gender = [];
        $education = FarmCatalogue::where('NAME','Education Status')->first();
        if(isset($education))
        {
            $data_education = $education->catalogue_value()->get();
        }
        $marial_status = FarmCatalogue::where('NAME','Marital Status')->first();
        if(isset($marial_status))
        {
            $data_marial_status = $marial_status->catalogue_value()->get();
        }
        return response()->json([
            'result' => true,
            'message' =>'Get Data Successfully',
            'data' => [
                'data_education' =>$data_education,
                'data_marial_status' =>$data_marial_status,
                'family_info' =>$family_info
            ]
            
        ]);
    }

    public function get_data_for_asset_info($id)
    {
        if(!isset($id))
        {
            return response()->json([
                'result' => false,
                'message' => 'Missing Parameter',
            ]);
        }
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        if ( $farmer_data->asset_info ) {
            $asset_info = $farmer_data->asset_info;
        } else {
            $asset_info = null;
        }
        $data_housing_owner = [];
        $data_house_type = [];
        $data_consumer_electronic = [];
        $data_vehicle = [];
        $housing_owner = FarmCatalogue::where('NAME','Housing Ownership')->first();
        if(isset($housing_owner))
        {
            $data_housing_owner = $housing_owner->catalogue_value()->get();
        }
        $house_type = FarmCatalogue::where('NAME','Housing Type')->first();
        if(isset($house_type))
        {
            $data_house_type = $house_type->catalogue_value()->get();
        }
        $consumer_electronic = FarmCatalogue::where('NAME','Consumer Electronics')->first();
        if(isset($consumer_electronic))
        {
            $data_consumer_electronic = $consumer_electronic->catalogue_value()->get();
        }
        $vehicle = FarmCatalogue::where('NAME','Vehicle')->first();
        if(isset($vehicle))
        {
            $data_vehicle = $vehicle->catalogue_value()->get();
        }
        return response()->json([
            'result' => true,
            'message' =>'Get Data Successfully',
            'data' => [
                'data_housing_owner' =>$data_housing_owner,
                'data_house_type' =>$data_house_type,
                'data_consumer_electronic' =>$data_consumer_electronic,
                'data_vehicle' =>$data_vehicle,
                'asset_info'=>$asset_info
            ]
            
        ]);
    }

    public function get_data_for_bank_info($id)
    {
        if(!isset($id))
        {
            return response()->json([
                'result' => false,
                'message' => 'Missing Parameter',
            ]);
        }
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        if ($farmer_data->bank_info ) {
            $bank_info = $farmer_data->bank_info;
        } else {
            $bank_info =null;
        }
        $data_account_type = [];
        $account_type = FarmCatalogue::where('NAME','Account Type')->first();
        if(isset($account_type))
        {
            $data_account_type = $account_type->catalogue_value()->get();
        }
        return response()->json([
            'result' => true,
            'message' =>'Get Data Successfully',
            'data' => [
                'data_account_type' =>$data_account_type,
                'bank_info'=>$bank_info
            ]
           
        ]);
    }

    public function get_data_for_finance_info($id)
    {
        if(!isset($id))
        {
            return response()->json([
                'result' => false,
                'message' => 'Missing Parameter',
            ]);
        }
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        if ($farmer_data->finance_info ) {
            $finance_info = $farmer_data->finance_info;
        } else {
            $finance_info = null;
        }
        $data_purpose = [];
        $purpose = FarmCatalogue::where('NAME','Loan Purpose')->first();
        if(isset($purpose))
        {
            $data_purpose = $purpose->catalogue_value()->get();
        }
        return response()->json([
            'result' => true,
            'message' =>'Get Data Successfully',
            'data' => [
                'data_purpose' =>$data_purpose,
                'finance_info' =>$finance_info
            ]

        ]);
    }

    public function get_data_for_insurance_info($id)
    {
        $data_crop = [];
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        if ($farmer_data->insurance_info ) {
            $insurance_info = $farmer_data->insurance_info;
        } else {
            $insurance_info = null;
        }
        $data_crop = CropInformation::All();
        return response()->json([
            'result' => true,
            'message' =>'Get Data Successfully',
            'data' => [
                'data_crop' =>$data_crop,
                'insurance_info'=>$insurance_info
            ]
        ]);
    }

    public function get_data_for_animal_husbandry($id)
    {
        $data_farm_animal = [];
        $data_fodder = [];
        $data_animal_housing = [];
        $data_animal_for_growth = [];
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        if ($farmer_data->animal_husbandry ) {
            $animal_husbandry = $farmer_data->animal_husbandry;
        } else {
            $animal_husbandry = null;
        }
        $farm_animal = FarmCatalogue::where('NAME','Animal Husbandry')->first();
        if(isset($farm_animal))
        {
            $data_farm_animal = $farm_animal->catalogue_value()->get();
        }
        $fodder = FarmCatalogue::where('NAME','Fodder')->first();
        if(isset($fodder))
        {
            $data_fodder = $fodder->catalogue_value()->get();
        }
        $animal_housing = FarmCatalogue::where('NAME','Animal Housing')->first();
        if(isset($animal_housing))
        {
            $data_animal_housing = $animal_housing->catalogue_value()->get();
        }
        $animal_for_growth = FarmCatalogue::where('NAME','Animal for Growth')->first();
        if(isset($animal_for_growth))
        {
            $data_animal_for_growth = $animal_for_growth->catalogue_value()->get();
        }
        return response()->json([
            'result' => true,
            'message' =>'Get Data Successfully',
            'data' => [
                'data_farm_animal' =>$data_farm_animal,
                'data_fodder' =>$data_fodder,
                'data_animal_housing' =>$data_animal_housing,
                'data_animal_for_growth' =>$data_animal_for_growth,
                'animal_husbandry'=>$animal_husbandry
            ]
           
        ]);
    }

    public function get_data_for_certificate_info($id)
    {
        $data_enrollment_place = [];
        $data_identity_proof = [];
        $data_gender = [];
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        if ($farmer_data->certificate_info ) {
            $certificate_info = $farmer_data->certificate_info;
        } else {
            $certificate_info = null;
        }
        $identity_proof = FarmCatalogue::where('NAME','Identity Proof')->first();
        if(isset($appoarch_road))
        {
            $data_identity_proof = $identity_proof->catalogue_value()->get();
        }
        $enrollment_place = FarmCatalogue::where('NAME','Enrollment Place')->first();
        if(isset($enrollment_place))
        {
            $data_enrollment_place = $enrollment_place->catalogue_value()->get();
        }
        $gender = FarmCatalogue::where('NAME','Gender')->first();
        if(isset($gender))
        {
            $data_gender = $gender->catalogue_value()->get();
        }
        return response()->json([
            'result' => true,
            'message' =>'Get Data Successfully',
            'data' => [
                'data_identity_proof' =>$data_identity_proof,
                'data_enrollment_place' =>$data_enrollment_place,
                'data_gender' =>$data_gender,
                'certificate_info'=>$certificate_info
            ]
            
        ]);
    }

    public function get_data_for_farm_equipment($id)
    {
        $data_farm_equipment = [];
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        if ($farmer_data->farm_equipment ) {
            $farm_equipment_info = $farmer_data->farm_equipment;
        } else {
            $farm_equipment_info = null;
        }
        $farm_equipment = FarmCatalogue::where('NAME','Farm Equipments')->first();
        if(isset($farm_equipment))
        {
            $data_farm_equipment = $farm_equipment->catalogue_value()->get();
        }
        return response()->json([
            'result' => true,
            'message' =>'Get Data Successfully',
            'data' => [
                'data_farm_equipment' =>$data_farm_equipment,
                'farm_equipment'=>$farm_equipment_info
            ]
            
        ]);
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
}
