<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetAvailableStockRequest extends BaseFormRequest
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
            'product_id' => 'required|numeric',
            'farmer_id' => 'required|numeric',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'product_id' => $this->route('product_id'),
            'farmer_id' => $this->route('farmer_id'),
        ]);
    }
}
