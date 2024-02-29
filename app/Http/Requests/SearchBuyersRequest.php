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
            'property_type'  => ['required','numeric'],
            'address'     => [], 
            'country'     => [],
            'city'        => ['required'], 
            'state'       => ['required'], 
            'zip_code'    => ['nullable', 'max:9', 'regex:/^[0-9]*$/'],
            'price'       => ['required','numeric'],

            'bedroom'      => ['nullable','numeric'],
            'bath'         => ['nullable','numeric'],
            'size'         => ['nullable','numeric'],
            'lot_size'     => ['required','numeric'],
            'build_year'   => ['nullable','numeric'],
            'arv'          => ['nullable','numeric'],
            'stories'   => ['nullable','numeric','max:3'],

            'parking' => ['nullable', 'in:'.implode(',', array_keys(config('constants.parking_values')))],
            
            //Location flaws
            'property_flaw' => ['required','array', 'in:'.implode(',', array_keys(config('constants.property_flaws')))],
    
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

            'zoning' => [],
            'utilities' => [],
            'sewer' => [],
            'market_preferance' => ['required','in:'.implode(',', array_keys(config('constants.market_preferances')))],
            // 'contact_preferance' => ['required','in:'.implode(',', array_keys(config('constants.contact_preferances')))],

        ];

        $rules['purchase_method'] = ['required','array', 'in:'.implode(',', array_keys(config('constants.purchase_methods')))];

        $rules['building_class'] = ['nullable', 'in:'.implode(',', array_keys(config('constants.building_class_values')))];
       

        //property_types :- 3 => Commercial - Retail, 4 => Condo, 7 => Land, 8 => Manufactured, 10 => Multi-Family - Commercial, 11 => Multi-Family - Residential,
        //    12 => Single Family, 13 => Townhouse, 14 => Mobile Home Park, 15 => Hotel/Motel,
    

        if(!in_array($this->property_type,[7])){

            $rules['parking'] = ['required', 'in:'.implode(',', array_keys(config('constants.parking_values')))];

        }

        if(!in_array($this->property_type,[7,14,15])){
          
            $rules['bedroom'] = ['required','numeric'];
            $rules['bath'] = ['required','numeric'];
        
        }

        if(!in_array($this->property_type,[7,14])){
            $rules['size'] = ['required','numeric'];
            $rules['build_year'] = ['required','numeric'];
            $rules['stories']    = ['required','numeric'];

        }

        if(!in_array($this->property_type,[4,7,8,12,13])){
            $rules['total_units']    = ['required','numeric'];
            $rules['building_class'] = ['required', 'in:'.implode(',', array_keys(config('constants.building_class_values')))];

            $rules['value_add'] = ['required'];
        }

        if(in_array($this->property_type,[7])){
            $rules['zoning'] = ['required', 'array', 'in:'.implode(',', array_keys(config('constants.zonings')))];
            $rules['utilities'] = ['required', 'in:'.implode(',', array_keys(config('constants.utilities')))];
            $rules['sewer'] = ['required', 'in:'.implode(',', array_keys(config('constants.sewers')))];
        }

        if(in_array($this->property_type,[14])){
            $rules['park'] = ['required', 'in:'.implode(',', array_keys(config('constants.park')))];
        }

        if(in_array($this->property_type,[15])){
            $rules['rooms']    = ['required','numeric'];
        
        }
    
        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'market_preferance.required' => 'The mls status field is required',
            'zip_code.max' => 'The digit should be less than 10.',
        ];
    }

    public function attributes()
    {
        return [
            'market_preferance' => 'mls status',
            'property_flaw'     => 'location flaws',
            'bedroom'     => 'bed',
            'size'        => 'sq ft',
            'lot_size'    => 'lot size sq Ft',
            'build_year'  => 'year built',
            'park'        => 'park owned/tenant owned',
        ];
    }


}
