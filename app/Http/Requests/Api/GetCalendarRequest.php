<?php

namespace App\Http\Requests\Api;
use App\Http\Requests\BaseFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class GetCalendarRequest extends BaseFormRequest
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
            'start_date' => 'required|string|date_format:d/m/Y',
            'end_date' => 'required|string|date_format:d/m/Y',
        ];
    }
}
