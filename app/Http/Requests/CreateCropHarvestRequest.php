<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCropHarvestRequest extends BaseFormRequest
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
            'harvest_date' => 'required|date',
            'crop_harvests' => 'required|array',
            'crop_harvests.*' => 'required|array',
            'crop_harvests.*.cultivation_id' => 'required|distinct',
            'crop_harvests.*.approx_harvest_qty' => 'required',
            'crop_harvests.*.price_per_unit' => 'required',
        ];
    }
}
