<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CropStageRequest extends FormRequest
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
            'name' => 'required|string',
            'crop_information_id' => 'required|numeric|exists:crop_informations,id',
            'crop_variety_id' => 'nullable|numeric|exists:crop_varieties,id',
            'date' => 'nullable|numeric',
            'status' => 'nullable|in:active,inactive',
        ];
    }
}
