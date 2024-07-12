<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StaffRequest extends FormRequest
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
            'user_type' => 'required|string|in:staff,warehouse_operator',
            'warehouse_id' => 'sometimes|nullable|integer',
            'cooperative_ids' => 'sometimes|nullable|array',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => 'required|string',
            'email' => 'required|string',
            'status' => 'required|string',
            'phone_number' => [
                'required',
                'string',
                Rule::unique('users')->ignore($this->staff?->user_id),
            ],
            'password' => (empty($this->staff->id) ? 'required' : 'nullable') . '|string|min:6|confirmed',
        ];
    }
}
