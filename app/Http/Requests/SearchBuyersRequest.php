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
            /*throw new HttpResponseException(
                response()->json([
                    'status' => false,
                    // 'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            );*/
            
            $errors = $validator->errors();

            // Prepare a custom error message for attachments
            $customErrors = [];
    
           // Collect all errors related to attachments.*
            $attachmentErrors = [];
            $otherErrors = [];
    
            // Iterate through the keys and check for attachment-related errors
            foreach ($errors->messages() as $key => $messages) {
                if (str_contains($key, 'attachments.') && !isset($attachmentErrors['attachments'])) {
                    // Take only the first error for each attachment
                    $attachmentErrors[] = $messages[0];
                }else {
                    // Collect errors for other fields
                    $otherErrors[$key] = $messages;
                }
            }
    
            // If there are any attachment errors, assign them to the 'attachments' key
            if (!empty($attachmentErrors)) {
                $customErrors['attachments'] = $attachmentErrors;
            }
            
            if (!empty($otherErrors)) {
                $customErrors = array_merge($customErrors, $otherErrors);
            }
    
            // Throw the HttpResponseException with the custom response
            throw new HttpResponseException(
                response()->json([
                    'errors' => $customErrors,
                ], 422)
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
            'address'     => [/*'required', 'string'*/], 
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

            'zoning' => [],
            'utilities' => [],
            'sewer' => [],
            'market_preferance' => ['required','in:'.implode(',', array_keys(config('constants.market_preferances')))],
            // 'contact_preferance' => ['required','in:'.implode(',', array_keys(config('constants.contact_preferances')))],

            'picture_link' => ['required', 'url'],

            'attachments' => ['required', 'array'],
            'attachments.*' => ['image', 'mimes:jpeg,png,jpg,svg', 'max:'.config('constants.profile_image_size')],
        ];

        $rules['purchase_method'] = ['required','array', 'in:'.implode(',', array_keys(config('constants.purchase_methods')))];

        $rules['building_class'] = ['nullable', 'in:'.implode(',', array_keys(config('constants.building_class_values')))];
       
        
        $rules['max_down_payment_percentage'] = [];
        $rules['max_down_payment_money'] = [];
        $rules['max_interest_rate'] = [];
        $rules['balloon_payment'] = [];

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

        if(!in_array($this->property_type,[3,4,7,8,12,13])){
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

        //'purchase_methods' = 1 => 'Cash', 2 => 'Hard Money', 3 => 'Private Financing', 4 => 'Conforming Loan', 5 => 'Creative Finance',
        
        if($this->purchase_method){
            if( array_intersect([5], $this->purchase_method) ){
                $rules['max_down_payment_percentage']   = ['required','numeric'];
                $rules['max_down_payment_money']        = ['required','numeric'];
                $rules['max_interest_rate']             = ['required','numeric'];
                $rules['balloon_payment']               = ['required'];
            }
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
            'zip_code.max'               => 'The digit should be less than 10.',
            'attachments.*.image'        => 'Each attachment must be an image.',
            'attachments.*.mimes'        => 'Each attachment must be a jpeg, png, jpg, or svg file.',
            'attachments.*.max'          => 'Each attachment must not exceed the maximum file size.',
        ];
    }

    public function attributes()
    {
        return [
            'market_preferance' => 'mls status',
            'property_flaw'     => 'location flaws',
            'bedroom'     => 'bed',
            'size'        => 'sq ft',
            'lot_size'    => 'lot size sq ft',
            'build_year'  => 'year built',
            'park'        => 'park owned/tenant owned',
            'max_down_payment_percentage' => 'down payment(%)',
            'max_down_payment_money' => 'down payment($)',
            'max_interest_rate' => 'interest rate(%)',
            'balloon_payment' => 'balloon payment',
        ];
    }


}
