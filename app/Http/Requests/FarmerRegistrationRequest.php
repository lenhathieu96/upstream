<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FarmerRegistrationRequest extends BaseFormRequest
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
            'phone_number' => 'required|string|unique:users,phone_number',
            'full_name' => 'nullable|string',
            'email' => 'nullable|email',
            'enrollment_date' => 'required|string',
            'country' => 'required|numeric',
            'province'  => 'required|numeric',
            'district' => 'required|numeric',
            'commune' => 'required|numeric',
            'village' => 'nullable|string',
            'gender' => 'required|string',
            'lat' => 'nullable|string',
            'lng' => 'nullable|string',
            'identity_proof' => 'nullable|string',
            'cooperative_id' => 'nullable|int'
        ];
    }
}
