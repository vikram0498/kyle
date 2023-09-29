<?php

namespace App\Http\Requests;

use App\Rules\CheckMaxValue;
use App\Rules\CheckMinValue;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class SupportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(
                response()->json([
                    'status' => false,
                    // 'message' => 'Validation error',
                    'validation_errors' => $validator->errors(),
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            );
        }

        parent::failedValidation($validator);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'name'  => ['required'], 
            'email'         => ['required', 'email:dns'],
            'phone_number'  => ['required','numeric','not_in:-'],
            'contact_preferance' => ['required','numeric','in:'.implode(',', array_keys(config('constants.contact_preferances')))],
            'message'            => ['required'], 
        ];
        return $rules;
    }
}
