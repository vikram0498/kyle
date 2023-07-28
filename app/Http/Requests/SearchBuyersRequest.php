<?php

namespace App\Http\Requests;

use App\Rules\CheckMaxValue;
use App\Rules\CheckMinValue;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SearchBuyersRequest extends FormRequest
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
                    'errors' => $validator->errors(),
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
            'property_type'  => ['required','int'],
            'address'     => [], 
            'country'     => [],
            'city'        => [], 
            'state'       => [], 
            'zip_code'    => [],
            'price'       => [],

            'bedroom_min' => [/*'required',*/ !empty($this->bedroom_max) ? new CheckMinValue($this->bedroom_max, 'bedroom_max') : ''],
            'bedroom_max' => [/*'required',*/ !empty($this->bedroom_min) ? new CheckMaxValue($this->bedroom_min, 'bedroom_min') : ''], 

            'bath_min' => ['nullable', !empty($this->bath_max) ? new CheckMinValue($this->bath_max, 'bath_max') : ''], 
            'bath_max' => ['nullable', !empty($this->bath_min) ? new CheckMaxValue($this->bath_min, 'bath_min') : ''], 
    
            'size_min' => [/*'required',*/ !empty($this->size_max) ? new CheckMinValue($this->size_max, 'size_max') : ''], 
            'size_max' => [/*'required',*/ !empty($this->size_min) ? new CheckMaxValue($this->size_min, 'size_min') : ''], 

            'lot_size_min' => ['nullable', !empty($this->lot_size_max) ? new CheckMinValue($this->lot_size_max, 'lot_size_max') : ''], 

            'lot_size_max' => ['nullable', !empty($this->lot_size_min) ? new CheckMaxValue($this->lot_size_min, 'lot_size_min') : ''], 

            'build_year_min' => ['nullable', !empty($this->build_year_max) ? new CheckMinValue($this->build_year_max, 'build_year_max') : ''], 

            'build_year_max' => ['nullable', !empty($this->build_year_min) ? new CheckMaxValue($this->build_year_min, 'build_year_min') : ''], 

            'arv_min' => ['nullable', !empty($this->arv_max) ? new CheckMinValue($this->arv_max, 'arv_max') : ''], 
            'arv_max' => ['nullable', !empty($this->arv_min) ? new CheckMaxValue($this->arv_min, 'arv_min') : ''], 


            'parking' => ['nullable', 'in:'.implode(',', array_keys(config('constants.parking_values')))],
            
            //Location flaws
            'property_flaw' => ['nullable','array', 'in:'.implode(',', array_keys(config('constants.property_flaws')))],
    
            'solar'    => [],
            'pool'     => [],
            'septic'   => [],
            'well'     => [],
            'age_restriction'     => [],
            'rental_restriction'  => [],
            'hoa'  => [],
            'tenant'           => [],
            'post_possession'  => [],
            'building_required'  => [],
            'foundation_issues'  => [],
            'mold'          => [],
            'fire_damaged'  => [],
            'rebuild'       => [],
            'squatters'     => [],
            'total_units'   => [],

        ];

        // $rules['buyer_type'] = [/*'required',*/'array', 'in:'.implode(',', array_keys(config('constants.buyer_types')))];

        $rules['purchase_method'] = ['required','array', 'in:'.implode(',', array_keys(config('constants.purchase_methods')))];

        $rules['max_down_payment_percentage'] = [];
        $rules['max_down_payment_money'] = [];
        $rules['max_interest_rate'] = [];
        $rules['balloon_payment'] = [];

        // $rules['unit_min'] = [];
        // $rules['unit_max'] = [];
        $rules['building_class'] = ['nullable', 'in:'.implode(',', array_keys(config('constants.building_class_values')))];
        $rules['value_add'] = [];

        return $rules;
    }


}
