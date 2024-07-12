<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Api\UploadsController;
use App\Http\Controllers\Controller;
use App\Http\Requests\CropInformationRequest;
use App\Models\CropInformation;
use Illuminate\Http\Request;

class CropMasterController extends Controller
{
    public function index(Request $request)
    {
        // $seasonCode = $request->input('season_code');
        // $fromPefiod = $request->input('from_period');
        // $toPefiod = $request->input('to_period');
        // $status = $request->input('status');

        $cropInformationQuery = CropInformation::with(['crop_category'])->orderByDesc('id');

        $cropInformations = $cropInformationQuery->paginate()->appends($request->except('page'));

        return view('admin.crop_master.index', compact('cropInformations'));
    }

    public function create()
    {
        $cropInformation = new CropInformation();

        return $this->edit($cropInformation);
    }

    public function edit(CropInformation $cropInformation)
    {
        return view('admin.crop_master.form', compact('cropInformation'));
    }

    public function show(CropInformation $cropInformation)
    {
        return redirect()->route('crop-infomations.edit', ['crop_information' => $cropInformation]);
    }


    public function store(CropInformationRequest $cropInformationRequest)
    {
        return $this->createOrUpdate($cropInformationRequest, new CropInformation());
    }


    public function update(CropInformationRequest $cropInformationRequest, CropInformation $cropInformation)
    {
        return $this->createOrUpdate($cropInformationRequest, $cropInformation);
    }

    private function createOrUpdate(CropInformationRequest $cropInformationRequest, CropInformation $cropInformation)
    {
        $isNewCropInformation = empty($cropInformation->id);
        $cropInformation->name = $cropInformationRequest->name;
        $cropInformation->crop_category_code = $cropInformationRequest->crop_category_code;
        $cropInformation->duration = $cropInformationRequest->duration;
        $cropInformation->duration_type = $cropInformationRequest->duration_type;
        $cropInformation->expected_expense = $cropInformationRequest->expected_expense;
        $cropInformation->expected_income = $cropInformationRequest->expected_income;
        $cropInformation->expected_yield = $cropInformationRequest->expected_yield;

        $cropInformation->save();

        if ($cropInformationRequest->has('photo')) {
            $photo_id = (new UploadsController)->upload_photo($cropInformationRequest->photo, $cropInformation->id, 'crop_information');
            $cropInformation->photo = $photo_id;
            $cropInformation->save();
        }

        return redirect()->route('crop-informations.edit', ['crop_information' => $cropInformation])->with([
            'success' => $isNewCropInformation ? 'Crop Master has been created!' : 'Crop Master has been updated!',
        ]);
    }

    public function destroy(CropInformation $cropInformation)
    {
        $cropInformation->delete();

        return redirect()->route('crop-informations.index')->with('success', 'The crop master has been deleted!');
    }
}
