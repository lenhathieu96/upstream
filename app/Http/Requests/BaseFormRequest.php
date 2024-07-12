<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        if ($this->expectsJson()) {
            throw new HttpResponseException(response()->json([
                'result' => false,
                'message' => $errors->first(),
                'errors' => $errors->toArray()
            ]));
        }

        parent::failedValidation($validator);
    }

    public function expectsJson(): bool
    {
        return true;
    }
}