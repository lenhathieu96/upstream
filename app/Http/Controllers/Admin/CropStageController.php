<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CropStageRequest;
use App\Models\CropInformation;
use App\Models\CropStage;
use App\Models\Cultivations;
use Illuminate\Http\Request;

class CropStageController extends Controller
{
    public function index(Request $request)
    {
        $cropStages = CropStage::get();

        return view("admin.crop_stage.index", compact('cropStages'));
    }

    public function create()
    {
        $cropStage = new CropStage();

        return $this->edit($cropStage);
    }

    public function edit(CropStage $cropStage)
    {
        $cropInformations = CropInformation::get();
        $cropVarieties = $cropStage?->crop_information?->crop_variety ?? [];

        return view('admin.crop_stage.form', compact('cropStage', 'cropInformations', 'cropVarieties'));
    }

    public function show(CropStage $cropStage)
    {
        return redirect()->route('crop-stages.edit', ['crop_stage' => $cropStage]);
    }

    public function store(CropStageRequest $cropStageRequest)
    {
        return $this->createOrUpdate($cropStageRequest, new CropStage());
    }

    public function update(CropStageRequest $cropStageRequest, CropStage $cropStage)
    {
        return $this->createOrUpdate($cropStageRequest, $cropStage);
    }

    private function createOrUpdate(CropStageRequest $cropStageRequest, CropStage $cropStage)
    {
        $isNewCropStage = empty($cropStage->id);
        $cropStage->name = $cropStageRequest->name;
        $cropStage->crop_information_id = $cropStageRequest->crop_information_id;
        $cropStage->crop_variety_id = $cropStageRequest->crop_variety_id;
        $cropStage->date = $cropStageRequest->date;
        $cropStage->status = $cropStageRequest->status;
        $cropStage->save();

        return redirect()->route('crop-stages.edit', ['crop_stage' => $cropStage->id])->with([
            'success' => $isNewCropStage ? 'Crop Stage has been created!' : 'Crop Stage has been updated!',
        ]);
    }

    public function destroy(CropStage $cropStage)
    {
        if ($cropStage->delete()) {
            return redirect()->route('crop-stages.index')->with('success', 'The Crop Stage has been deleted!');
        }

        abort(500);
    }

    public function updateStatus(Request $request)
    {
        $cropStage = CropStage::find($request->id);
        $cropStage->status = $request->status;
        $cropStage->save();
    }
}
