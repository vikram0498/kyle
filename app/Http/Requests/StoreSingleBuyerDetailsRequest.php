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
           
            // 'country'     => ['required'],
            'city'        => [/*'required'*/], 
            'state'       => [/*'required'*/], 
            // 'company_name'   => ['required'], 

            'price_min' => ['required','numeric', !empty($this->price_max) ? new CheckMinValue($this->price_max, 'price_max') : ''], 
            'price_max' => ['required', 'numeric', !empty($this->price_min) ? new CheckMaxValue($this->price_min, 'price_min') : ''], 

            'bedroom_min' => ['required','numeric', !empty($this->bedroom_max) ? new CheckMinValue($this->bedroom_max, 'bedroom_max') : ''], 
            'bedroom_max' => ['required', 'numeric', !empty($this->bedroom_min) ? new CheckMaxValue($this->bedroom_min, 'bedroom_min') : ''], 

            'bath_min' => ['required', 'numeric', !empty($this->bath_max) ? new CheckMinValue($this->bath_max, 'bath_max') : ''], 
            'bath_max' => ['required', 'numeric', !empty($this->bath_min) ? new CheckMaxValue($this->bath_min, 'bath_min') : ''], 

            'size_min' => ['required', 'numeric', !empty($this->size_max) ? new CheckMinValue($this->size_max, 'size_max') : ''], 
            'size_max' => ['required', 'numeric', !empty($this->size_min) ? new CheckMaxValue($this->size_min, 'size_min') : ''], 

            'lot_size_min' => ['required', 'numeric', !empty($this->lot_size_max) ? new CheckMinValue($this->lot_size_max, 'lot_size_max') : ''], 

            'lot_size_max' => ['required', 'numeric', !empty($this->lot_size_min) ? new CheckMaxValue($this->lot_size_min, 'lot_size_min') : ''], 

            'build_year_min' => ['required', 'numeric', !empty($this->build_year_max) ? new CheckMinValue($this->build_year_max, 'build_year_max') : ''], 

            'build_year_max' => ['required', 'numeric', !empty($this->build_year_min) ? new CheckMaxValue($this->build_year_min, 'build_year_min') : ''], 

            
            'arv_min' => ['numeric', !empty($this->arv_max) ? new CheckMinValue($this->arv_max, 'arv_max') : ''], 
            'arv_max' => ['numeric', !empty($this->arv_min) ? new CheckMaxValue($this->arv_min, 'arv_min') : ''], 

            'parking' => ['required', 'numeric'],
            'property_type' => ['required','array', 'in:'.implode(',', array_keys(config('constants.property_types')))],
            'property_flaw' => ['nullable','array', 'in:'.implode(',', array_keys(config('constants.property_flaws')))],
            'buyer_type' => ['required','numeric'],
            'purchase_method' => ['required','array', 'in:'.implode(',', array_keys(config('constants.purchase_methods')))],

         
            'stories_min' => ['required','numeric','max:3', !empty($this->stories_max) ? new CheckMinValue($this->stories_max, 'stories_max') : ''], 

            'stories_max' => ['required', 'numeric', 'max:3', !empty($this->stories_min) ? new CheckMaxValue($this->stories_min, 'stories_min') : ''],


            'zoning' => [/*'required',*/'array', 'in:'.implode(',', array_keys(config('constants.zonings')))],
            'utilities' => [/*'required',*/'numeric','in:'.implode(',', array_keys(config('constants.utilities')))],
            'sewer' => [/*'required',*/'numeric','in:'.implode(',', array_keys(config('constants.sewers')))],
            'market_preferance' => ['required','numeric','in:'.implode(',', array_keys(config('constants.market_preferances')))],
            'contact_preferance' => ['required','numeric','in:'.implode(',', array_keys(config('constants.contact_preferances')))],

        ];

        // if(!empty($this->purchase_method) && in_array(5, $this->purchase_method)){
        //     $rules['max_down_payment_percentage'] = ['required','numeric','between:0,100'];
        //     $rules['max_interest_rate'] = ['required'];
        //     $rules['balloon_payment'] = ['required'];
        // }

        if(!empty($this->property_type) && array_intersect([2,10,11,14,15], $this->property_type)){
            $rules['unit_min'] = ['required', 'numeric', !empty($this->unit_max) ? new CheckMinValue($this->unit_max, 'unit_max') : ''];
            $rules['unit_max'] = ['required', 'numeric', !empty($this->unit_min) ? new CheckMaxValue($this->unit_min, 'unit_min') : ''];
            // $rules['value_add'] = ['required'];
            $rules['building_class'] = ['required','array', 'in:'.implode(',', array_keys(config('constants.building_class_values')))];
        }

        if($this->formName == 'copy-form'){
            $rules['address']     = [];
            $rules['zip_code']    = []; 

        }else{
            $rules['address']     = ['required'];
            $rules['zip_code']    = ['required','min:5','max:10']; 

        }

        return $rules;
    }
}
