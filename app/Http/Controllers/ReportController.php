<?php

namespace App\Http\Controllers;

use App\Exports\DistributionExport;
use App\Exports\FarmerBalanceExport;
use App\Http\Requests\DistributionReportRequest;
use App\Http\Requests\TransactionReportRequest;
use App\Models\Commune;
use App\Models\CropInformation;
use App\Models\CropVariety;
use App\Models\Cultivations;
use App\Models\Distribution;
use App\Models\FarmerDetails;
use App\Models\FarmLand;
use App\Models\FarmLandLatLng;
use App\Models\SeasonMaster;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
class ReportController extends Controller
{
    // Farmer Report Page
    public function farmer_report(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $farmerCode = $request->input('farmer_code');
        $farmerName = $request->input('farmer_name');
        $phoneNumber = $request->input('phone_number');
        $provinceId = $request->input('province_id');
        $staffId = $request->input('staff_id');
        $exportExcel = $request->input('export_excel');

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

        if($exportExcel) {
            return response()->streamDownload(function () use ($farmerDetailQuery) {
                $this->exportFarmer($farmerDetailQuery);
            }, 'Farmer.csv');
        }

        $farmerDetails = $farmerDetailQuery->paginate(10)->appends($request->except('page'));

        return view('report.farmer_report_index', compact('farmerDetails', 'farmerCode', 'farmerName', 'startDate', 'endDate',  'phoneNumber', 'provinceId', 'staffId'));
    }

    private function exportFarmer($farmerDetailQuery)
    {
        $file = fopen('php://output', 'w');
        fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF))); // Fix for Excel

        fputcsv($file, [
            'Enrollment Date',
            'Farmer Code',
            'Farmer Name',
            'Phone Number',
            'Field Officer',
            'Gender',
            'Province',
            'District',
            'Commune',
            'Total No of Plots',
            'Total land holding(HA)',
            'Status',
        ]);

        $farmerDetailQuery->each(function (FarmerDetails $farmerDetail) use ($file) {
            fputcsv($file, [
                $farmerDetail->enrollment_date,
                $farmerDetail->farmer_code,
                $farmerDetail->full_name,
                $farmerDetail->phone_number,
                $farmerDetail->staff?->name,
                $farmerDetail->gender,
                $farmerDetail->provinceRelation?->province_name,
                $farmerDetail->districtRelation?->district_name,
                $farmerDetail->communeRelation?->commune_name,
                round($farmerDetail->sum_total_land_holding / 10000, 2),
                ucwords($farmerDetail->status)
            ]);
        }, 100);

        fclose($file);
    }

    public function farmland_report(Request $request)
    {
        $farmerCode = $request->input('farmer_code');
        $farmerName = $request->input('farmer_name');
        $phoneNumber = $request->input('phone_number');
        $staffId = $request->input('staff_id');
        $exportExcel = $request->input('export_excel');

        $farmLandQuery = FarmLand::select('farm_lands.*')->orderByDesc('created_at');

        $needJoinOption = false;
        if (!empty($farmerCode) || !empty($farmerName) || !empty($phoneNumber) || !empty($staffId)) {
            $needJoinOption = true;
        }

        if ($needJoinOption) {
            $farmLandQuery->leftJoin('farmer_details', 'farmer_details.id', '=', 'farm_lands.farmer_id');
        }

        if (!empty($farmerCode)) {
            $farmLandQuery->where('farmer_details.farmer_code', $farmerCode);
        }

        if (!empty($farmerName)) {
            $farmLandQuery->Where('farmer_details.full_name', 'like', '%' . $farmerName . '%');
        }

        if (!empty($phoneNumber)) {
            $farmLandQuery->where('farmer_details.phone_number', $phoneNumber);
        }

        if (!empty($staffId)) {
            $farmLandQuery->where('farmer_details.staff_id', $staffId);
        }

        if($exportExcel) {
            return response()->streamDownload(function () use ($farmLandQuery) {
                $this->exportFarmLand($farmLandQuery);
            }, 'FarmLand.csv');
        }

        $farmLands = $farmLandQuery->paginate(10)->appends($request->except('page'));

        return view('report.farmland_report_index', compact('farmLands', 'farmerCode','farmerName','phoneNumber', 'staffId'));
    }

    private function exportFarmLand($farmLandQuery)
    {
        $file = fopen('php://output', 'w');
        fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF))); // Fix for Excel

        fputcsv($file, [
            'Farmland Name',
            'Farmer Code',
            'Farmer Name',
            'Farmer Phone Number',
            'Field Officer',
            'Land Ownership',
            'Total land holding(HA)',
            'Actual Area(HA)',
        ]);

        $farmLandQuery->each(function (FarmLand $farmLand) use ($file) {
            fputcsv($file, [
                $farmLand->farm_name,
                $farmLand->farmer_details?->farmer_code,
                $farmLand->farmer_details?->full_name,
                $farmLand->farmer_details?->phone_number,
                $farmLand->farmer_details?->staff?->name,
                $farmLand->land_ownership,
                $farmLand->total_land_holding,
                $farmLand->actual_area,
            ]);
        }, 100);

        fclose($file);
    }

    public function singel_farmland_location($id)
    {
        $farm_land_data = FarmLand::find($id);
        $plot_data = [];
        $data_farmer = FarmerDetails::select(['full_name','farmer_code','farmer_photo'])->find($farm_land_data->farmer_id);
        $cultivation_data = $farm_land_data->cultivation()->first();
        if(isset($cultivation_data))
        {
            $season_data = SeasonMaster::find($cultivation_data->season_id);
            $crop_information = CropInformation::find($cultivation_data->crop_id);
            $farm_land_data->crop_name = $crop_information?->name;
            $farm_land_data->season_period_from = $season_data?->from_period;
            $farm_land_data->season_period_to = $season_data?->to_period;
            $farm_land_data->est_yeild = $cultivation_data?->est_yield;
            $farm_land_data->harvest_date = $cultivation_data?->expect_date;
        }
        else
        {
            $farm_land_data->crop_name = 'N/A';
            $farm_land_data->season_period_from = 'N/A';
            $farm_land_data->season_period_to = 'N/A';
            $farm_land_data->est_yeild = 'N/A';
            $farm_land_data->harvest_date ='N/A';
        }
        $farm_land_data->farmer_name = $data_farmer->full_name;
        $farm_land_data->farmer_code = $data_farmer->farmer_code;
        $farm_land_data->farmer_photo = uploaded_asset($data_farmer->farmer_photo);
        $data_ploting = $farm_land_data->farm_land_lat_lng()->get();
        foreach($data_ploting as $each_data_ploting)
        {
            if($each_data_ploting->order == 1)
            {
                $farm_land_data->lat = $each_data_ploting->lat;
                $farm_land_data->lng = $each_data_ploting->lng;
            }
            $subplot = [
                'lat'=>$each_data_ploting->lat,
                'lng'=>$each_data_ploting->lng
            ];
            array_push($plot_data,$subplot);

        }
        if(count($data_ploting)>0)
        {
            $subplot_final = [
                'lat'=>$data_ploting[0]->lat,
                'lng'=>$data_ploting[0]->lng
            ];
            array_push($plot_data,$subplot_final);
        }

        $farm_land_data->plot_data = $plot_data;
        // dd($farm_land_data);
        return view('farm_land.single_farmland_loaction',['farm_land_data'=>$farm_land_data]);
    }

    public function cultivation_report(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $cropId = $request->input('crop_id');
        $cropVariety = $request->input('crop_variety');
        $seasonId = $request->input('season_id');
        $staffId = $request->input('staff_id');
        $farmerCode = $request->input('farmer_code');
        $farmerName = $request->input('farmer_name');
        $exportExcel = $request->input('export_excel');
        $varieties = [];

        if (!empty($cropId)) {
            $varieties = CropVariety::where('crop_id', $cropId)->get()->pluck('name', 'id')->all();
        }

        $needJoinOption = false;
        if (!empty($staffId) || !empty($farmerCode) || !empty($farmerName)) {
            $needJoinOption = true;
        }

        $cutivationQuery = Cultivations::select('cultivations.*');

        if ($needJoinOption) {
            $cutivationQuery->leftJoin('farm_lands', 'farm_lands.id', '=', 'cultivations.farm_land_id')
                ->leftJoin('farmer_details', 'farmer_details.id', '=', 'farm_lands.farmer_id');
        }

        if (!empty($startDate)) {
            $startDateString = Carbon::createFromFormat('d/m/Y', $startDate)->format('Y-m-d');
            $cutivationQuery->whereRaw("DATE_FORMAT(STR_TO_DATE(sowing_date, '%d/%m/%Y'), '%Y-%m-%d') >= ?", [$startDateString]);
        }

        if (!empty($endDate)) {
            $endDateString = Carbon::createFromFormat('d/m/Y', $endDate)->format('Y-m-d');
            $cutivationQuery->whereRaw("DATE_FORMAT(STR_TO_DATE(sowing_date, '%d/%m/%Y'), '%Y-%m-%d') <= ?", [$endDateString]);
        }

        if (!empty($cropId)) {
            $cutivationQuery->where('crop_id', $cropId);
        }

        if (!empty($cropVariety)) {
            $varietyName = CropVariety::find($cropVariety)?->name;
            $cutivationQuery->where('crop_variety', $varietyName);
        }

        if (!empty($seasonId)) {
            $cutivationQuery->where('cultivations.season_id', $seasonId);
        }

        if (!empty($staffId)) {
            $cutivationQuery->where('farmer_details.staff_id', $staffId);
        }

        if (!empty($farmerCode)) {
            $cutivationQuery->where('farmer_details.farmer_code', $farmerCode);
        }

        if (!empty($farmerName)) {
            $cutivationQuery->where('farmer_details.full_name', 'like', '%' . $farmerName . '%');
        }

        if($exportExcel) {
            //$cultivations = $cutivationQuery->get();

            return response()->streamDownload(function () use ($cutivationQuery) {
                $this->exportCultivation($cutivationQuery);
            }, 'Cultivation.csv');
        }

        $cultivations = $cutivationQuery->paginate(10)->appends($request->except('page'));

        return view('report.cultivation_report_index', compact('cultivations', 'startDate', 'endDate', 'cropId', 'cropVariety', 'seasonId', 'staffId', 'farmerCode', 'farmerName', 'varieties'));
    }

    private function exportCultivation($cutivationQuery)
    {
        $file = fopen('php://output', 'w');
        fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF))); // Fix for Excel

        fputcsv($file, [
            'Sowing Date',
            'Crop',
            'Variety',
            'Harvest Season',
            'Field Officer',
            'Farmer Code',
            'Farmer Name',
            'Est Yield(Kg)',
        ]);

        $cutivationQuery->each(function ($cultivation) use ($file) {
            fputcsv($file, [
                $cultivation->sowing_date,
                $cultivation->crops_master?->name,
                $cultivation->crop_variety,
                $cultivation->season?->season_name,
                $cultivation->farm_land?->farmer_details?->staff?->name,
                $cultivation->farm_land?->farmer_details?->farmer_code,
                $cultivation->farm_land?->farmer_details?->full_name,
                $cultivation->est_yield,
            ]);
        }, 100);

        fclose($file);
    }

    /**
     * export by this format: https://docs.google.com/spreadsheets/d/1B42ODuhcpz3rH7IXYz7XC1unHGwOPaLZ-MO1t0sdgf0/edit#gid=0
     */
    public function exportPlotting(Request $request)
    {
        $farmerDetailsQuery = FarmerDetails::has('farm_lands')->with('farm_lands');

        //dd(FarmerDetails::find(45)->full_address);

        if ($request->isMethod('post')) {
            return response()->streamDownload(function () use ($farmerDetailsQuery) {
                $this->exportPlottingCSV($farmerDetailsQuery);
            }, 'farmer-analysis.csv');
        }

        return view('report.plotting');
    }

    public function exportPlottingCSV($farmerDetailsQuery)
    {
        $file = fopen('php://output', 'w');
        fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF))); // Fix for Excel

        fputcsv($file, [
            'Enrollment Date',
            'Farmer Name',
            'Farmer Location',
            'Total Land holding',
            'Land Ownership',
            'Latitude',
            'Longitude',
            'Plotting Coordinates',
            'Variety Name',
            'Sowing Date',
            'Est Harvest Date',
            'Est Yield',
        ]);

        $farmerDetailsQuery->each(function (FarmerDetails $farmerDetail) use ($file) {
            foreach($farmerDetail->farm_lands as $farm_land) {
                foreach (($farm_land?->cultivation ?? []) as $cultivation) {
                    $farmlandLatLongs = FarmLandLatLng::where('farmer_id', $farmerDetail->id)
                        ->where('farm_land_id', $farm_land->id)
                        ->orderBy('order')
                        ->get()
                        ->map(function ($item) {
                            return [
                                $item->lat,
                                $item->lng,
                            ];
                        })->all();


                    fputcsv($file, [
                        $farmerDetail->enrollment_date,
                        $farmerDetail->full_name,
                        $farmerDetail->full_address,
                        $farm_land->total_land_holding,
                        $farm_land->land_ownership,
                        $farm_land->lat,
                        $farm_land->lng,
                        json_encode($farmlandLatLongs),
                        $cultivation->crop_variety,
                        $cultivation->sowing_date,
                        $cultivation->expect_date,
                        $cultivation->est_yield,
                    ]);
                }
            }
        }, 100);


        fclose($file);
    }

    public function distributionReport(DistributionReportRequest $request)
    {
        $distributions = Distribution::query()->latest('created_at');

        if ($request->filled('staff_id')) {
            $distributions->where('agent_id', $request->input('staff_id'));
        }

        if ($request->filled('receipt_no')) {
            $distributions->where('receipt_no', $request->input('receipt_no'));
        }

        if ($request->filled('start_date')) {
            $distributions->where('distribution_date', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $distributions->where('distribution_date', '<=', $request->input('end_date'));
        }

        if ($request->filled('farmer_code')) {
            $distributions->whereHas('farmer', function ($query) use ($request) {
                $query->where('farmer_code', $request->input('farmer_code'));
            });
        }

        if ($request->filled('farmer_name')) {
            $distributions->whereHas('farmer', function ($query) use ($request) {
                $query->where('full_name', 'like', '%' . $request->input('farmer_name') . '%');
            });
        }

        if ($request->filled('cooperative_id')) {
            $distributions->whereHas('farmer', function ($query) use ($request) {
                $query->where('cooperative_id', $request->input('cooperative_id'));
            });
        }

        if ($request->filled('export_type')) {
            $type = $request->input('export_type');
            return Excel::download((new DistributionExport($distributions, $type)), $type . '_export_' . date('Y-m-d') . '.xlsx');
        }

        return view('report.distribution_report_index', [
            'distributions' => $distributions->paginate($request->input('per_page', 10))->appends($request->except('page')),
            'startDate' => $request->input('start_date'),
            'endDate' => $request->input('end_date'),
            'farmerCode' => $request->input('farmer_code'),
            'farmerName' => $request->input('farmer_name'),
            'cooperativeId' => $request->input('cooperative_id'),
            'staffId' => $request->input('staff_id'),
            'receiptNo' => $request->input('receipt_no'),
        ]);
    }

    public function farmerBalanceReport(TransactionReportRequest $request)
    {
        $query = FarmerDetails::query()->whereHas('transactions');

        if ($request->filled('farmer_code')) {
            $query->where('farmer_code', $request->input('farmer_code'));
        }

        if ($request->filled('farmer_name')) {
            $query->where('full_name', '%' . $request->input('farmer_name') . '%');
        }

        if ($request->filled('amount_type')) {
            $query->whereHas('faAccount', function ($builder) use ($request) {
                $builder->where('outstanding_amount', $request->input('amount_type') === 'Debit Balance' ? '<' : '>', 0);
            });
        }

        if ($request->filled('acc_no')) {
            $query->whereHas('faAccount', function ($builder) use ($request) {
                $builder->where('acc_no', $request->input('acc_no'));
            });
        }

        if ($request->filled('export_type')) {
            $type = $request->input('export_type');
            return Excel::download((new FarmerBalanceExport($query)), $type . '_export_' . date('Y-m-d') . '.xlsx');
        }
        $farmers = $query->paginate($request->input('per_page', 10));

        return view('report.farmer_balance_report_index', [
            'farmers' => $farmers->appends($request->except('page')),
            'farmerName' => $request->input('farmer_name'),
            'farmerCode' => $request->input('farmer_code'),
            'accNo' => $request->input('acc_no'),
            'amountType' => $request->input('amount_type'),
        ]);
    }

    public function getCropGrowthReport($parcelId)
    {
        $cultivation = Cultivations::where('parcel_id', $parcelId)->first();
        return view('report.crop_growth_report', compact('parcelId', 'cultivation'));
    }

    public function getGcMapHtml(Request $request)
    {
        $parcelId = $request->parcel_id;

        $html = null;
        if ($request->type == 'map') {
            $html = view('report.gc-component.gc-map', compact('parcelId'))->render();
        } elseif ($request->type = 'chart') {
            $html = view('report.gc-component.gc-chart', compact('parcelId'))->render();
        }
        return response()->json(['html' => $html]);
    }
}
