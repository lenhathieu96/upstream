<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportFarmerBalanceRequest;

use App\Models\FarmerDetails;
use App\Services\ReportService;

class ReportController extends Controller
{
    public function __construct(private ReportService $service)
    {
    }

    public function farmerBalanceReport(ReportFarmerBalanceRequest $request)
    {
        $authUser = $request->user('sanctum');
        $farmers = FarmerDetails::whereHas('distributions')->where('staff_id', $authUser->staff->id);

        return response()->streamDownload(function () use ($farmers) {
            $this->service->exportFarmerBalanceReport($farmers);
        }, 'Farmer.csv');
    }
}