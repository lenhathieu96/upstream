<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DistributionReportRequest extends FormRequest
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
            'farmer_code' => 'sometimes|nullable|string',
            'farmer_name' => 'sometimes|nullable|string',
            'staff_id' => 'sometimes|nullable|int',
            'receipt_no' => 'sometimes|nullable|string',
            'start_date' => 'sometimes|nullable',
            'end_date' => 'sometimes|nullable',
            'export_type' => 'sometimes|nullable|string'
        ];
    }
}
