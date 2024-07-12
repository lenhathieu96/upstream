<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\apps\Calendar;
use App\Http\Controllers\Controller;
use App\Http\Requests\CropCalendarRequest;
use App\Models\CropCalendar;
use App\Models\CropCalendarDetail;
use App\Models\CropInformation;
use Illuminate\Http\Request;

class CropCalendarController extends Controller
{
    public function index(Request $request)
    {
        $cropCalendarQuery = CropCalendar::orderByDesc('id');

        $cropCalendars = $cropCalendarQuery->paginate()->appends($request->except('page'));

        return view('admin.crop_calendar.index', compact('cropCalendars'));
    }

    public function create()
    {
        $cropCalendar = new CropCalendar();

        return $this->edit($cropCalendar);
    }

    public function edit(CropCalendar $cropCalendar)
    {
        $cropInformations = CropInformation::pluck('name', 'id')->toArray();

        return view('admin.crop_calendar.form', compact('cropCalendar', 'cropInformations'));
    }

    public function show(CropCalendar $cropCalendar)
    {
        return redirect()->route('crop-calendars.edit', ['crop_calendar' => $cropCalendar]);
    }


    public function store(CropCalendarRequest $cropCalendarRequest)
    {
        return $this->createOrUpdate($cropCalendarRequest, new CropCalendar());
    }


    public function update(CropCalendarRequest $cropCalendarRequest, CropCalendar $cropCalendar)
    {
        return $this->createOrUpdate($cropCalendarRequest, $cropCalendar);
    }

    private function createOrUpdate(CropCalendarRequest $cropCalendarRequest, CropCalendar $cropCalendar)
    {
        //dd($cropCalendarRequest->all());
        $isNewCropCalendar = empty($cropCalendar->id);
        $cropCalendar->crop_info_id = $cropCalendarRequest->crop_info_id;
        $cropCalendar->calendar_name = $cropCalendarRequest->calendar_name;
        $cropCalendar->country_id = $cropCalendarRequest->country_id;
        $cropCalendar->province_id = $cropCalendarRequest->province_id;
        $cropCalendar->district_id = $cropCalendarRequest->district_id;
        $cropCalendar->status = $cropCalendarRequest?->status == 'on' ? 'active' : 'inactive';
        $cropCalendar->save();

        // Delete old crop calendar
        $cropCalendar->cropCalendarDetails()->each(function ($cropCalendarDetail) {
            $cropCalendarDetail->delete();
        });

        $cropCalendarDetailCollection = collect();
        foreach ($cropCalendarRequest->input('calendar_detail', []) as $calendarDetailData) {
            $cropCalendarDetail = new CropCalendarDetail();
            $cropCalendarDetail->activity_title = $calendarDetailData['activity_title'];
            $cropCalendarDetail->crop_activity_id = $calendarDetailData['crop_activity_id'];
            $cropCalendarDetail->crop_stage_id = $calendarDetailData['crop_stage_id'];
            $cropCalendarDetail->duration = $calendarDetailData['duration'];
            $cropCalendarDetail->duration_uom = $calendarDetailData['duration_uom'];
            $cropCalendarDetail->activity_description = $calendarDetailData['activity_description'];
            $cropCalendarDetail->repetition = $calendarDetailData['repetition'];
            $cropCalendarDetail->lead_time = $calendarDetailData['lead_time'];
            $cropCalendarDetail->is_base_on_sowing_date = !empty($calendarDetailData['is_base_on_sowing_date']) ? 1 : 0;
            $cropCalendarDetail->status = !empty($calendarDetailData['status']) ? 'active' : 'inactive';

            $cropCalendarDetailCollection->push($cropCalendarDetail);
        }

        $cropCalendar->cropCalendarDetails()->saveMany($cropCalendarDetailCollection);

        return redirect()->route('crop-calendars.edit', ['crop_calendar' => $cropCalendar])->with([
            'success' => $isNewCropCalendar ? 'Crop Calendar has been created!' : 'Crop Calendar has been updated!',
        ]);
    }

    public function destroy(CropCalendar $cropCalendar)
    {
        $cropCalendar->delete();

        return redirect()->route('crop-calendars.index')->with('success', 'The crop calendarr has been deleted!');
    }

    public function ajaxGetCalendarView(Request $request)
    {
        $itemIndex = uniqid();

        return view('admin.crop_calendar.calendar_details', ['calendarDetail' => new CropCalendarDetail, 'itemIndex' => $itemIndex]);
    }

    public function updateStatus(Request $request)
    {
        $cropCalendar = CropCalendar::find($request->id);
        $cropCalendar->status = $request->status;
        $cropCalendar->save();
    }
}
