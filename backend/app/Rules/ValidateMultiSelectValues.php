<?php
  
namespace App\Rules;
  
use Illuminate\Contracts\Validation\Rule;
  
class ValidateMultiSelectValues implements Rule
{
    private $customMessage;

    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->customMessage = '';
        
        if($attribute == 'parking'){            
            $constValues = config('constants.parking_values');            
        } 
        
        else if($attribute == 'property_flaw'){
            $constValues = config('constants.property_flaws');
        } 
        
        else if($attribute == 'property_type'){
            if(empty($value)){
                $this->customMessage = "The $attribute field is required.";
                return false;
            } 
            $constValues = config('constants.property_types');
        } 
        
        else if($attribute == 'buyer_type'){
            if(empty($value)){
                $this->customMessage = "The $attribute field is required.";
                return false;
            } 
            $constValues = config('constants.buyer_types');
        } 
        
        else if($attribute == 'purchase_method'){
            if(empty($value)){
                $this->customMessage = "The $attribute field is required.";
                return false;
            } 
            $constValues = config('constants.purchase_methods');
        } 
        
        else if($attribute == 'building_class'){
            if(empty($value)){
                $this->customMessage = "The $attribute field is required.";
                return false;
            } 
            $constValues = config('constants.building_class_values');
        } 

        if(!is_array($value)){
            $this->customMessage = "The $attribute must be an array.";
            return false;                
        }

        foreach($value as $val){
            if(!array_key_exists($val,  $constValues)){
                $this->customMessage = "The selected $attribute is invalid.";
                return false;
            }
        }
        return true;
    }
   
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        // dd($this->customMessage);
        // return 'The :attribute is not valid.';
        return $this->customMessage;
    }
}