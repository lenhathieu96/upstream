<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CropCalendarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'crop_info_id' => 'required|numeric|exists:crop_informations,id',
            'calendar_name' => 'required|string',
            'country_id' => 'required|numeric|exists:countries,id',
            'province_id' => 'required|numeric|exists:provinces,id',
            'district_id' => 'required|numeric|exists:districts,id',
            'status' => 'nullable',
            'calendar_detail.*.activity_title'=> 'nullable|string',
            'calendar_detail.*.crop_activity_id'=> 'nullable|string|exists:crop_activities,id',
            'calendar_detail.*.crop_stage_id'=> 'nullable|string|exists:crop_stages,id',
            'calendar_detail.*.duration'=> 'nullable|numeric',
            'calendar_detail.*.duration_uom'=> 'nullable|string',
            'calendar_detail.*.activity_description'=> 'nullable|string',
            'calendar_detail.*.repetition'=> 'nullable|numeric',
            'calendar_detail.*.lead_time'=> 'nullable|numeric',
            'calendar_detail.*.is_base_on_sowing_date'=> 'nullable',
            'calendar_detail.*.status'=> 'nullable',
        ];
    }
}
