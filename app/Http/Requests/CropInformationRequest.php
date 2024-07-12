<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CropInformationRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'crop_category_code' => 'required|string|exists:crop_categories,code',
            'photo' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:10000',
            'duration' => 'nullable|numeric',
            'duration_type' => 'nullable|string|in:days,months,years',
            'expected_expense' => 'nullable|numeric',
            'expected_income' => 'nullable|numeric',
            'expected_yield' => 'nullable|numeric',
        ];
    }
}
