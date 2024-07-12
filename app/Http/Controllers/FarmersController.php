<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Commune;
use App\Models\Cooperative;
use App\Models\Country;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\District;
use App\Models\FamilyInfo;
use App\Models\FarmerCountable;
use App\Models\FarmerDetails;
use App\Models\FarmLand;
use App\Models\FarmLandLatLng;
use App\Models\LogActivities;
use Yajra\DataTables\DataTables;
use App\Models\Province;
use App\Models\Staff;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\UploadFarmerService;
use Illuminate\Support\Facades\Log;

class FarmersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $log_actitvities = new LogActivities();
    }



    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $farmerCode = $request->input('farmer_code');
        $farmerName = $request->input('farmer_name');
        $phoneNumber = $request->input('phone_number');
        $provinceId = $request->input('province_id');
        $staffId = $request->input('staff_id');

        $farmerDetailQuery = FarmerDetails::orderByDesc('created_at')
            ->withCount(['farm_lands'])
            ->withSum('farm_lands as sum_total_land_holding', 'total_land_holding');

        if (!empty($startDate)) {
            $farmerDetailQuery->where('enrollment_date', '>=', $startDate);
        }

        if (!empty($endDate)) {
            $farmerDetailQuery->where('enrollment_date', '<=', $endDate);
        }

        if (!empty($farmerCode)) {
            $farmerDetailQuery->where('farmer_code', $farmerCode);
        }

        if (!empty($farmerName)) {
            $farmerDetailQuery->Where('full_name', 'like', '%' . $farmerName . '%');
        }

        if (!empty($phoneNumber)) {
            $farmerDetailQuery->where('phone_number', $phoneNumber);
        }

        if (!empty($provinceId)) {
            $farmerDetailQuery->where('province', $provinceId);
        }

        if (!empty($staffId)) {
            $farmerDetailQuery->where('staff_id', $staffId);
        }

        $farmerDetails = $farmerDetailQuery->paginate(10)->appends($request->except('page'));

        return view('farmer.index', compact('farmerDetails', 'farmerCode', 'farmerName', 'startDate', 'endDate',  'phoneNumber', 'provinceId', 'staffId'));
    }

    public function split_with_whitespace($keyword)
    {
        return preg_split('/\s+/u', $keyword, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $farmerDetail = FarmerDetails::find($id);
        if (empty($farmerDetail)) {
            return redirect()->route('farmer.index');
        }

        $qrcode = QrCode::size(54)->generate($farmerDetail->farmer_url);
        
        return view('farmer.show',['farmerDetail'=> $farmerDetail,'qrcode'=>$qrcode]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function farmer_location(Request $request)
    {
        $farmer_data = FarmerDetails::all();
        // foreach ($farmer_data as $each_farmer)
        // {
        //     $farm_land_data = $each_farmer->farm_lands()->get();
        //     $cultivation_crop = $each_farmer->cultivation_crop()->get();
        //     dd($cultivation_crop);
        // }
        // dd($farmer_data);
        return view('farmer.farmer_location',['farmers_data'=>$farmer_data]);
    }

    public function dtajax(Request $request)
    {
        if($request->ajax())
        {
            if($request->search == "")
            {
                // $farmer = FarmerDetails::all(['id','farmer_code','full_name','phone_number','gender','staff_id'])->sortDesc();
                $farmer = FarmerDetails::query()->orderByDesc('id');
            }
            else
            {
                $farmer = FarmerDetails::where("full_name", 'like', '%'.$request->search.'%')->orWhere("phone_number",$request->search)->orWhere("farmer_code",$request->search);
                $farmer = $farmer->get()->sortDesc();
            }
            $out =  DataTables::of($farmer)->make(true);
            $data = $out->getData();
            for($i=0; $i < count($data->data); $i++) {
                $output = '';
                $output .= ' <a href="'.url(route('farmer.show',['id'=>$data->data[$i]->id])).'" class="btn btn-primary btn-xs"  data-toggle="tooltip" title="Show Details" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-eye"></i></a>';
                
                $data->data[$i]->action = (string)$output;

                $staff = Staff::find($data->data[$i]->staff_id);
                $data->data[$i]->staff_name = $staff?->name;
            }
            $out->setData($data);
            // dd($out);
            return $out;
        }
    }
    

    public function distribute_transation(Request $request)
    {
      
        $result = [];
        // $result = $response->json();
        $out =  Datatables::of($result)->make(false);
        return $out;   
    }
    
    // public function registration(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'phone_number' => 'required|string|unique:users,phone_number',
    //         'username' => 'string|unique:users,username',
    //         'password' => 'required|string|min:5',
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'result' => false,
    //             'message' => $validator->messages(),
    //         ]);
    //     }
    //     $user = New User();
    //     $farmer_details = New FarmerDetails();

    //     if($request->email == null)
    //     {
    //         $email = "";
    //     }
    //     $user_data = [
    //         'name' =>$request->full_name,
    //         'user_type'=>'farmer',
    //         'username'=>$request->full_name,
    //         'email'=>$email,
    //         'password'=>Hash::make($request->password),
    //         'phone_number'=>$request->phone_number,
    //         'email_verified_at' => ''
    //     ];

    //     $user = new User(); 
    //     $user->name = $request->full_name; 
    //     $user->user_type = "farmer"; 
    //     $user->username = $request->full_name; 
    //     $user->email = $email; 
    //     $user->password = Hash::make($request->password); 
    //     $user->phone_number = $request->phone_number; 
    //     $user->email_verified_at = ""; 
    //     $user->save();

    //     // $user->create($user_data);
    //     if (!empty($request->all()['farmer_photo'])) {
    //         $farmer_photo = [];
    //         foreach ($request->all()['farmer_photo'] as $photo) {                        
    //             $id = (new UploadsController)->upload_photo($photo,$user->id);

    //             if (!empty($id)) {
    //                 array_push($farmer_photo, $id);
    //             }
    //         }    
    //     }

    //     if (!empty($request->all()['id_proof_photo'])) {
    //         $id_proof_photo = [];
    //         foreach ($request->all()['id_proof_photo'] as $photo) {                        
    //             $id = (new UploadsController)->upload_photo($photo,$user->id);

    //             if (!empty($id)) {
    //                 array_push($id_proof_photo, $id);
    //             }
    //         }    
    //     }

    //     $ldate = date('Ymd');
    //     $current_timestamp = Carbon::now()->timestamp; 
    //     $farmer_code = 'Farmer-'.$ldate.'-'.$current_timestamp;
    //     $data_farmer_details =[
    //         'user_id'=>$user->id,
    //         'enrollment_date' =>$request->enrollment_date,
    //         'enrollment_place'=>$request->enrollment_place,
    //         'full_name'=>$request->full_name,
    //         'phone_number'=>$request->phone_number,
    //         'identity_proof'=>$request->identity_proof,
    //         'country'=>$request->country,
    //         'province'=>$request->province,
    //         'district'=>$request->district,
    //         'commune'=>$request->commune,
    //         'village'=>$request->village,
    //         'lng'=>$request->lng,
    //         'lat'=>$request->lat,
    //         'proof_no'=>$request->proof_no,
    //         'gender'=>$request->gender,
    //         'farmer_code'=>$farmer_code,
    //         'dob'=>$request->dob,
    //         'farmer_photo'=>implode(',', $farmer_photo),
    //         'id_proof_photo'=>implode(',', $id_proof_photo),
    //     ];
    //     $farmer_data = $farmer_details->create($data_farmer_details);
    //     return response()->json([
    //         'result' => true,
    //         'message' => 'Farmer Registration Successfully',
    //         'farmer_data' =>$farmer_data
    //     ]);
    // }

    public function importCSV(Request $request)
    {
        $faker = \Faker\Factory::create();

        $filePath = $request->csvFile->path(); // csvFile is request name input
        if ($file = fopen($filePath, "r")) {
            while(($row = fgetcsv($file, null, ",")) !== FALSE) {     
                if (ucwords($row[0]) == "First Name") {
                    continue;
                }

                // temporary added staff
                // $staff = Staff::find(35);

                if (isset($row[9]) && ($row[9] == 'Hau Tran' || $row[9] == '0394328444')) {
                    $staff = Staff::where('phone_number', '0394328444')->first();
                } else if (isset($row[9]) && ($row[9] == 'Ngoan Nguyen' || $row[9] == '09674959444')) {
                    $staff = Staff::where('phone_number', '09674959444')->first();
                } else {
                    $staff = Staff::where('id', '>=', 35)->has('farmer_details', '<', 200)->first();
                    if (empty($staff)) {
                        $staff = Staff::find(3);
                    }
                }


                $fullName = trim($row[0] . ' ' . $row[1]);

                $phoneNumber = str_replace('o', '0', $row[2]);
                $phoneNumber = str_replace('nt', '', $phoneNumber);
                $phoneNumber = preg_replace("/[^0-9]/", '', $phoneNumber);

                $province = Province::where('province_name', $this->formatString($row[4]))->first();
                $dicstrict = District::where('district_name', $this->formatString($row[5]))->first();
                $commune = Commune::where('commune_name', $this->formatString($row[6]))->first();

                $village = $row[7];
                $totalLandHolding = isset($row[8]) ? $row[8] : 0;

                $countable = FarmerCountable::find(1);
                $farmer_code = 'FA' . date('Y') . date('m') . date('d') . $countable->count_number;
                $countable->update(['count_number'=>$countable->count_number +=1]);

                $farmerDetail = FarmerDetails::create([
                    'user_id' => 3,
                    'staff_id' => $staff->id,
                    'full_name' => $fullName,
                    'phone_number' => $phoneNumber,
                    'country' => 1,
                    'province' => $province?->id ?? 0,
                    'district' => $dicstrict?->id ?? 0,
                    'commune' => $commune?->id ?? 0,
                    'village' => $village,
                    // auto generate field
                    'enrollment_date' => now()->toDateString(),
                    'gender' => 'Male',
                    'farmer_code' => $farmer_code,
                ]);

                if (!empty($totalLandHolding)) {
                    FarmLand::create([
                        'farmer_id' => $farmerDetail->id,
                        'farm_name' => 'plot1',
                        'total_land_holding' => $totalLandHolding * 10000,
                        'actual_area' => $totalLandHolding  * 10000,
                        'land_ownership' => 'Own'
                    ]);
                }
            }

            fclose($file);
        }

        return back()->with(['success' => 'Import farmer succesfully']);
    }

    public function importCSV_Farmer_Details(Request $request)
    {
        $filePath = $request->csvFile->path(); // csvFile is request name input
        if ($file = fopen($filePath, "r")) {
            while(($row = fgetcsv($file, null, ",")) !== FALSE) {     
                if (ucwords($row[0]) == "Managed By") {
                    continue;
                }

                $staff = Staff::where('id', '>=', 35)->has('farmer_details', '<', 200)->first();
                if (empty($staff)) {
                    $staff = Staff::find(3);
                }

                $staffName = trim($row[1]);
                $farmerCode = trim($row[2]);
                $farmerName = trim($row[3]);
                $gender =  $row[4];
                $age = $row[5];
                $dob = null;

                if ($age) {
                    $dob = (date('Y') - $age) . '-01-01';
                }

                $phoneNumber = str_replace('o', '0', $row[6]);
                $phoneNumber = str_replace('+84', '', $phoneNumber);
                $phoneNumber = preg_replace("/[^0-9]/", '', $phoneNumber);

                $parentName = $row[7];
                $village = $row[8];
                $communeName = $this->formatString($row[9]);
                $location = $this->formatString($row[10]);
                $enrollmentDate = $row[11];
                $totalLandHolding = $row[12];

                // processing
                $locationInfo = explode(',', $location);
                $dictrictName = trim($locationInfo[0]);
                $provinceName = trim($locationInfo[1]);
                $communeId = 0;

                $province = Province::where('province_name', $provinceName)->first();
                $district = District::where('district_name', $dictrictName)->first();
                if ($district) {
                    $commune = Commune::where('district_id', $district->id)
                        ->where('commune_name', $communeName)
                        ->first();

                    $communeId = $commune?->id ?? 0;
                }

                $farmerDetail = FarmerDetails::create([
                    'user_id' => 3,
                    'staff_id' => $staff->id,
                    'full_name' => $farmerName,
                    'phone_number' => $phoneNumber,
                    'country' => 1,
                    'province' => $province->id ?? 0,
                    'district' => $district->id ?? 0,
                    'commune' => $communeId,
                    'village' => $village,
                    'enrollment_date' => $enrollmentDate,
                    'gender' => $gender,
                    'farmer_code' => $farmerCode,
                    'dob' => $dob
                ]);

                $staff->first_name = $staffName;
                $staff->save();

                if (!empty($parentName)) {
                    $farmilyInfos = new FamilyInfo();
                    $farmilyInfos->farmer_id = $farmerDetail->id;
                    $farmilyInfos->parent_name = $parentName;
                    $farmilyInfos->save();
                }

                FarmLand::create([
                    'farmer_id' => $farmerDetail->id,
                    'farm_name' => 'plot1',
                    'total_land_holding' => $totalLandHolding  * 10000,
                    'actual_area' => $totalLandHolding  * 10000,
                    'land_ownership' => 'Own'
                ]);
            }

            fclose($file);
        }

        return back()->with(['success' => 'Import farmer succesfully']);
    }

    public function importCSV_Area_Audit(Request $request)
    {
        set_time_limit('3600');
        $filePath = $request->csvFile->path(); // csvFile is request name input
        if ($file = fopen($filePath, "r")) {
            while(($row = fgetcsv($file, null, ",")) !== FALSE) {     
                if ($row[0] == "FarmerCode") {
                    continue;
                }

                $farmerCode = trim($row[0]);
                $farmerName = trim($row[1]);
                $plotOwner = trim($row[2]);
                $plotName = trim($row[3]);
                $coordinates = $this->formatString($row[4]);
                $latitude = $this->formatString($row[5]);
                $longtitude = $this->formatString($row[6]);
                $isCropAudit = trim($row[7]);
                $totalLandHolding = trim($row[8]);

                $farmerDetail = FarmerDetails::where('farmer_code', $farmerCode)->first();
                
                if ($farmerDetail && $totalLandHolding) {
                    $farmLand = FarmLand::create([
                        'farmer_id' => $farmerDetail->id,
                        'farm_name' => $plotName,
                        'total_land_holding' => $totalLandHolding * 10000,
                        'actual_area' => $totalLandHolding * 10000,
                        'land_ownership' => 'Own',
                        'lat' =>  $latitude,
                        'lng' =>  $longtitude,
                    ]);

                    $coordinateInfos = explode(',', $coordinates);
                    foreach ($coordinateInfos as  $key => $coordinateInfo) {
                        $coordinateItem = explode(' ', $coordinateInfo);
                        if (isset($coordinateItem[0]) && isset($coordinateItem[1])) {
                            FarmLandLatLng::create([
                                'farmer_id' => $farmerDetail->id,
                                'farm_land_id' => $farmLand->id,
                                'order' => $key + 1,
                                'lat' => $coordinateItem[0],
                                'lng' => $coordinateItem[1],
                            ]);
                        }
                    }
                }

            }
            fclose($file);
        }

        return back()->with(['success' => 'import area audit succesfully']);
    }

    public function updateFarmlandLatLng(Request $request)
    {
        set_time_limit('3600');
        $filePath = $request->csvFile->path(); // csvFile is request name input
        if ($file = fopen($filePath, "r")) {
            while(($row = fgetcsv($file, null, ",")) !== FALSE) {     
                if ($row[0] == "FarmerCode") {
                    continue;
                }

                $farmerCode = trim($row[0]);
                $farmerName = trim($row[1]);
                $plotOwner = trim($row[2]);
                $plotName = trim($row[3]);
                $coordinates = $this->formatString($row[4]);
                $latitude = $this->formatString($row[5]);
                $longtitude = $this->formatString($row[6]);
                $isCropAudit = trim($row[7]);
                $totalLandHolding = trim($row[8]);

                $farmerDetail = FarmerDetails::where('farmer_code', $farmerCode)->first();
                
                if ($farmerDetail) {
                    $farmLand = FarmLand::where('farmer_id', $farmerDetail->id)->first();
                    if ($farmLand) {
                        if (empty($latitude) && empty($longtitude)) {
                            $farmLand->delete();
                        } else {
                            $farmLand->lat = $latitude;
                            $farmLand->lng = $longtitude;
                            $farmLand->save();
                        }
                    }
                }

            }
            fclose($file);
        }

        FarmLand::whereNull('lat')->orWhereNull('lng')->delete();

        return back()->with(['success' => 'update farmland lat long succesfully']);
    }

    public function formatString($str)
    {
        $str = ucwords(strtolower($str));
        $str = str_replace('Province','', $str);
        $str = str_replace('Thị Xã','', $str);
        $str = str_replace('TX','', $str);
        $str = str_replace('Xã ','', $str);
        $str = str_replace('Tx','', $str);
        $str = preg_replace('/NULL/i', '', $str);
        $str = trim($str);
        
        return $str;
    }

    public function importFarmer(Request $request)
    {
        set_time_limit('3600');
        if ($request->isMethod('post')) {

            $uploadFarmerService = new UploadFarmerService();
            $filePath = $request->csvFile->path(); // csvFile is request name input
            if ($file = fopen($filePath, "r")) {
                while(($row = fgetcsv($file, null, ",")) !== FALSE) {     
                    if ($row[0] == "First Name") {
                        continue;
                    }

                    $row = $this->trimRow($row);

                    $provinceId = get_province_id($row[6]);
                    $districtId = get_district_id($row[7], $provinceId);
                    $communeId = get_commune_id($row[8], $districtId);

                    $farmerData = [
                        'first_name' => $row[0],
                        'last_name' => $row[1],
                        'not_use_phone_number' => $row[2],
                        'phone_number' => $row[3],
                        'email' => 'upstream_' . uniqid() . '@gmail.com',
                        'country' => 1,
                        'province' => $provinceId,
                        'district' => $districtId,
                        'commune' => $communeId,
                        'village' => $row[9],
                        'plot_location' => $row[10],
                        'plot_name' => $row[11],
                        'cultivated_area'=> $row[12],
                        'season_id'=> get_season_id($row[13]),
                        'crop_id'=> get_crop_id($row[14]),
                        'variety'=> $row[15],
                        'cooperative_id'=> $this->getCooperativeId($row[16]),
                        'staff_id' => $this->getStaffIdBaseOnCooperative($row[16]),
                        'tenant' => 'SNV'
                    ];

                    try {
                        $uploadFarmerService->importFarmer($farmerData);
                    } catch (\Exception $exception) {  
                        Log::error($exception->getMessage());
                    }
                }
            }

            return back()->with(['success' => 'import farmer succesfully']);
        }

        return view('import.farmer');
    }

    public function getStaffIdBaseOnCooperative($cooperativeName)
    {
        if ($cooperativeName == 'Đông Thành') {
            $staff = Staff::where('email', 'thanhfo@gmail.com')->first();
            return !empty($staff) ? $staff->id : 0;
        }

        if ($cooperativeName == 'Phước Tiến') {
            $staff = Staff::where('email', 'bayfo@gmail.com')->first();
            return !empty($staff) ? $staff->id : 0;
        }

        if ($cooperativeName == 'Thạnh Lợi' || $cooperativeName == 'Hưng Thạnh') {
            $staff = Staff::where('email', 'duongfo@gmail.com')->first();
            return !empty($staff) ? $staff->id : 0;
        }
    }

    public function getCooperativeId($cooperativeName)
    {
        $cooperative = Cooperative::where('name', $cooperativeName)->first();
        if (empty($cooperative)) {
            $cooperative = Cooperative::where('name', 'like', '%' . $cooperativeName . '%')->first();
        }

        return !empty($cooperative) ? $cooperative->id : null;
    }

    public function trimRow($row)
    {
        $result = [];
        foreach ($row as $rowData) {
            $result[] = trim($rowData);
        }
        
        return $result;
    }
}
