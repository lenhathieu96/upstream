<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateDistributionRequest extends BaseFormRequest
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
            'distribution_date' => ['required', 'date_format:Y-m-d', 'before_or_equal:' . now()->format('Y-m-d')],
            'farmer_id' => 'required|exists:farmer_details,id',
            'products' => 'required|array',
            'products.*.product_id' => 'required|int',
            'products.*.product_name' => 'required|string',
            'products.*.category_id' => 'required|int',
            'products.*.category_name' => 'required|string',
            'products.*.quantity' => 'required|int',
            'products.*.price_per_unit' => 'required|numeric',
            'products.*.available_stocks' => 'required',
            'products.*.unit' => 'required|string',
            'products.*.stock_id' => 'sometimes|int',
        ];
    }
}
