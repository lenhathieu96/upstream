<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetSeasonMastersRequest extends BaseFormRequest
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
            'season_name' => 'sometimes|nullable',
            'from_period' => 'sometimes|nullable',
            'to_period' => 'sometimes|nullable',
            'status' => 'sometimes|nullable',
        ];
    }
}
