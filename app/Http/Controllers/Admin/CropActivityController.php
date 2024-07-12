<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CropActivityRequest;
use App\Models\CropActivity;
use Illuminate\Http\Request;

class CropActivityController extends Controller
{
    public function index(Request $request)
    {
        $cropActivities = CropActivity::get();

        return view("admin.crop_activity.index", compact('cropActivities'));
    }

    public function create()
    {
        $cropActivity = new CropActivity();

        return $this->edit($cropActivity);
    }

    public function edit(CropActivity $cropActivity)
    {
        return view('admin.crop_activity.form', compact('cropActivity'));
    }

    public function show(CropActivity $cropActivity)
    {
        return redirect()->route('crop-activities.edit', ['crop_activity' => $cropActivity]);
    }

    public function store(CropActivityRequest $cropActivityRequest)
    {
        return $this->createOrUpdate($cropActivityRequest, new CropActivity());
    }

    public function update(CropActivityRequest $cropActivityRequest, CropActivity $cropActivity)
    {
        return $this->createOrUpdate($cropActivityRequest, $cropActivity);
    }

    private function createOrUpdate(CropActivityRequest $cropActivityRequest, CropActivity $cropActivity)
    {
        $isNewCropActivity = empty($cropActivity->id);
        $cropActivity->name = $cropActivityRequest->name;
        $cropActivity->status = $cropActivityRequest->status;
        $cropActivity->save();

        return redirect()->route('crop-activities.edit', ['crop_activity' => $cropActivity->id])->with([
            'success' => $isNewCropActivity ? 'Crop Activity has been created!' : 'Crop Activity has been updated!',
        ]);
    }

    public function destroy(CropActivity $cropActivity)
    {
        if ($cropActivity->delete()) {
            return redirect()->route('crop-activities.index')->with('success', 'The Crop Activity has been deleted!');
        }

        abort(500);
    }

    public function updateStatus(Request $request)
    {
        $cropActivity = CropActivity::find($request->id);
        $cropActivity->status = $request->status;
        $cropActivity->save();
    }
}
