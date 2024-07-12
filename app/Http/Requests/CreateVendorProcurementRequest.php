<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVendorProcurementRequest extends BaseFormRequest
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
            'season_id' => 'required|int',
            'lat' => 'nullable',
            'lng' => 'nullable',
            'order_id' => 'required|int',
            'order_code' => 'required|string',
            'post_harvest_qc' => 'sometimes|nullable|array',
            'product_name' => 'required|string',
            'product_id' => 'required|int|exists:sale_intentions,product_id',
            'quantity' => 'required|int',
            'qc_photo' => 'nullable|array',
            'qc_photo.*' => 'nullable|image',
            'order_photo' => 'required|array',
            'order_photo.*' => 'required|image',
        ];
    }
}
