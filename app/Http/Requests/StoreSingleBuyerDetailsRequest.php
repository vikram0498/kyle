<?php

namespace App\Http\Requests;

use App\Rules\CheckMaxValue;
use App\Rules\CheckMinValue;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class StoreSingleBuyerDetailsRequest extends FormRequest
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
            'first_name'  => ['required'], 
            'last_name'   => ['required'], 
            'email'       => ['required', 'email', 'unique:buyers,email,NULL,id,deleted_at,NULL'],
            'phone'       => ['required', 'numeric', /*'digits:10'*/'not_in:-'], 
            'address'     => ['required'], 
            'country'     => ['required'],
            'city'        => ['required'], 
            'state'       => ['required'], 
            'zip_code'    => ['required'],
            // 'company_name'   => ['required'], 

            'bedroom_min' => ['required', !empty($this->bedroom_max) ? new CheckMinValue($this->bedroom_max, 'bedroom_max') : ''], 
            'bedroom_max' => ['required', !empty($this->bedroom_min) ? new CheckMaxValue($this->bedroom_min, 'bedroom_min') : ''], 

            'bath_min' => ['required', !empty($this->bath_max) ? new CheckMinValue($this->bath_max, 'bath_max') : ''], 
            'bath_max' => ['required', !empty($this->bath_min) ? new CheckMaxValue($this->bath_min, 'bath_min') : ''], 

            'size_min' => ['required', !empty($this->size_max) ? new CheckMinValue($this->size_max, 'size_max') : ''], 
            'size_max' => ['required', !empty($this->size_min) ? new CheckMaxValue($this->size_min, 'size_min') : ''], 

            'lot_size_min' => ['required', !empty($this->lot_size_max) ? new CheckMinValue($this->lot_size_max, 'lot_size_max') : ''], 

            'lot_size_max' => ['required', !empty($this->lot_size_min) ? new CheckMaxValue($this->lot_size_min, 'lot_size_min') : ''], 

            'build_year_min' => ['required', !empty($this->build_year_max) ? new CheckMinValue($this->build_year_max, 'build_year_max') : ''], 

            'build_year_max' => ['required', !empty($this->build_year_min) ? new CheckMaxValue($this->build_year_min, 'build_year_min') : ''], 

            
            'arv_min' => ['required', !empty($this->arv_max) ? new CheckMinValue($this->arv_max, 'arv_max') : ''], 
            'arv_max' => ['required', !empty($this->arv_min) ? new CheckMaxValue($this->arv_min, 'arv_min') : ''], 

            'parking' => ['required','array', 'in:'.implode(',', array_keys(config('constants.parking_values')))],
            'property_type' => ['required','array', 'in:'.implode(',', array_keys(config('constants.property_types')))],
            'property_flaw' => ['nullable','array', 'in:'.implode(',', array_keys(config('constants.property_flaws')))],
            'buyer_type' => ['required','array', 'in:'.implode(',', array_keys(config('constants.buyer_types')))],
            'purchase_method' => ['required','array', 'in:'.implode(',', array_keys(config('constants.purchase_methods')))],
        ];

        // if(!empty($this->purchase_method) && in_array(5, $this->purchase_method)){
        //     $rules['max_down_payment_percentage'] = ['required'];
        //     $rules['max_interest_rate'] = ['required'];
        //     $rules['balloon_payment'] = ['required'];
        // }

        if(!empty($this->property_type) && array_intersect([2,10,11,14,15], $this->property_type)){
            $rules['unit_min'] = ['required', !empty($this->unit_max) ? new CheckMinValue($this->unit_max, 'unit_max') : ''];
            $rules['unit_max'] = ['required', !empty($this->unit_min) ? new CheckMaxValue($this->unit_min, 'unit_min') : ''];
            // $rules['value_add'] = ['required'];
            $rules['building_class'] = ['required','array', 'in:'.implode(',', array_keys(config('constants.building_class_values')))];
        }

        return $rules;
    }
}
