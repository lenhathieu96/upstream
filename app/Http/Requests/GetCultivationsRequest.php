<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetCultivationsRequest extends BaseFormRequest
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
            'farm_land_id' => 'sometimes|nullable|int',
            'season_id' => 'sometimes|nullable|int',
            'crop_id' => 'sometimes|nullable|int',
            'crop_variety'=> 'sometimes|nullable|string',
        ];
    }
}
