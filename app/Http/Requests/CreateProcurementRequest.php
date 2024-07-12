<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProcurementRequest extends BaseFormRequest
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
            "vehicle_id" => 'required|numeric',
            'booking_date' => 'sometimes|nullable',
            'warehouse_id' => 'required|numeric',
            'lat' => 'sometimes|nullable|numeric',
            'lng' => 'sometimes|nullable|numeric',
            'procurement_details' => 'required|array',
            'procurement_details.*.crop_harvest_detail_id' => 'required|exists:crop_harvest_details,id',
            'procurement_details.*.actual_qty' => 'required|numeric|min:0',
        ];
    }
}
