<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Buyer;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Helpers\BuyerImportColumnMapper;

class BuyersImport implements ToModel, WithStartRow, WithChunkReading
{
    private $rowCount = 0;
    private $insertedCount = 0;
    private $softDeletedCount = 0;
    private $softDeletedIndices = [];
    private $skippedCount = 0;
    private $skippedErrors = [];

    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 1000; // Process 1000 rows at a time
    }

    public function totalRowCount(): int
    {
        return $this->rowCount;
    }

    public function insertedCount(): int
    {
        return $this->insertedCount;
    }

    public function softDeletedCount()
    {
        return $this->softDeletedCount;
    }

    public function skippedRowCount()
    {
        return $this->skippedCount;
    }

    public function getSkippedErrors()
    {
        return $this->skippedErrors;
    }

    private function logSkippedRow($rowIndex, $reason)
    {
        $logMessage = "Row " . ($rowIndex + 1) . ": $reason";
        $this->skippedErrors[] = $logMessage;

        Log::info($logMessage);
    }

    public function logSummary()
    {
        Log::info("Import Buyers CSV Summary:");

        if (count($this->skippedErrors) > 0) {
            foreach ($this->skippedErrors as $error) {
                Log::info($error);
            }
        }

        if (count($this->softDeletedIndices) > 0) {
            Log::info("Soft-Deleted Rows Index: " . implode(', ', $this->softDeletedIndices));
        }

        Log::info("Total Rows Processed: " . $this->totalRowCount());
        Log::info("Total Inserted Rows: " . $this->insertedCount());
        Log::info("Total Soft-Deleted Rows: " . $this->softDeletedCount());
        Log::info("Total Skipped Rows: " . $this->skippedRowCount());

    }

    private function columnIndex($col_name){
        return BuyerImportColumnMapper::getColumnIndex($col_name);
    }

    public function model(array $row)
    { 
        $buyerArr = [];
       
        $firstName = $this->modifiedString($row[$this->columnIndex('first_name')]); $lastName = $this->modifiedString($row[$this->columnIndex('last_name')]);
        
        if(!empty($firstName) && !empty($lastName)){
            $buyerArr['user_id']        = auth()->user()->id;
            $buyerArr['first_name']     = ucwords($firstName);
            $buyerArr['last_name']      = ucwords($lastName);
            $buyerArr['name']           = $firstName.' '.$lastName;
            $email                      = strtolower($this->modifiedString($row[$this->columnIndex('email')]));
            $countryCode                = (int)config('constants.twilio_country_code');
            
            if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) ) {
                
                $buyerArr['email'] = $email;
                $phone = $this->modifiedString($row[$this->columnIndex('phone_number')]);
                if(!empty($phone) && is_numeric($phone)){
                    
                    $buyerArr['country_code'] = $countryCode;
                    $buyerArr['phone'] = $phone;
                    
                    $city = $this->modifiedString($row[$this->columnIndex('city')]); 
                    $state = $this->modifiedString($row[$this->columnIndex('state')]);                                                  
                                            
                    $stateId = NULL;
                    $cityId = NULL;
                
                    $countryId= config('constants.default_country');

                    $statesArray = array_map('ucwords', array_map('trim', explode(',', $state)));
                    $stateData = DB::table('states')->where('country_id', $countryId)->whereIn('name', $statesArray)->pluck('id');
                    if($stateData->count() > 0){
                        $stateId = $stateData;

                        $citiesArray = array_map('ucwords', array_map('trim', explode(',', $city)));
                        $cityData = DB::table('cities')->whereIn('state_id', $stateData)->whereIn('name', $citiesArray)->pluck('id');
                        if($cityData->count() > 0){
                            $cityId = $cityData;
                        }
                    }                               

                    $buyerArr['country'] = DB::table('countries')->where('id', $countryId)->first()->name;
                    $buyerArr['city'] = $cityId  ? json_encode($cityId->toArray()) : NULL;
                    $buyerArr['state'] = $stateId ? json_encode($stateId->toArray()) : NULL;
                                                    
                    $companyName = strtolower($this->modifiedString($row[$this->columnIndex('company_name')]));
                    $companyName = (empty($companyName) || $companyName == 'blank') ? NULL : $companyName;
                    $buyerArr['company_name'] = $companyName;

                    //Start number values min and max
                    $bedroomMin     = strtolower($this->modifiedString($row[$this->columnIndex('bed_room_min')]));       
                    $bedroomMax     = strtolower($this->modifiedString($row[$this->columnIndex('bed_room_max')]));

                    $bathMin        = strtolower($this->modifiedString($row[$this->columnIndex('bath_min')]));
                    $bathMax        = strtolower($this->modifiedString($row[$this->columnIndex('bath_max')]));

                    $sizeMin        = strtolower($this->modifiedString($row[$this->columnIndex('size_min')]));      
                    $sizeMax        = strtolower($this->modifiedString($row[$this->columnIndex('size_max')]));

                    $lotSizeMin     = strtolower($this->modifiedString($row[$this->columnIndex('lot_size_min')]));      
                    $lotSizeMax     = strtolower($this->modifiedString($row[$this->columnIndex('lot_size_max')]));

                    $buildYearMin   = strtolower($this->modifiedString($row[$this->columnIndex('build_year_min')]));      
                    $buildYearMax   = strtolower($this->modifiedString($row[$this->columnIndex('build_year_min')]));
                    
                    $priceMin       = strtolower($this->modifiedString($row[$this->columnIndex('price_min')]));      
                    $priceMax       = strtolower($this->modifiedString($row[$this->columnIndex('price_max')]));

                    
                    $stories_min    = strtolower($this->modifiedString($row[$this->columnIndex('stories_min')]));      
                    $stories_max     = strtolower($this->modifiedString($row[$this->columnIndex('stories_max')]));
                    //End number values min and max

                    $bathMin        = (empty($bathMax) || $bathMin == 'blank') ? NULL : (!is_numeric($bathMin) ? NULL : $bathMin);
                    $bathMax        = (empty($bathMax) || $bathMax == 'blank') ? NULL : (!is_numeric($bathMax) ? NULL : $bathMax);                                    

                    $lotSizeMin     = (empty($lotSizeMin) || $lotSizeMin == 'blank') ? NULL : (!is_numeric($lotSizeMin) ? NULL : $lotSizeMin);
                    $lotSizeMax     = (empty($lotSizeMax) || $lotSizeMax == 'blank') ? NULL : (!is_numeric($lotSizeMax) ? NULL : $lotSizeMax);

                    $buildYearMin   = (empty($buildYearMin) || $buildYearMin == 'blank') ? NULL : (!is_numeric($buildYearMin) ? NULL : $buildYearMin);
                    $buildYearMax   = (empty($buildYearMax) || $buildYearMax == 'blank') ? NULL : (!is_numeric($buildYearMax) ? NULL : $buildYearMax);
                    
                    
                    $priceMin         = (empty($priceMin) || $priceMin == 'blank') ? NULL : (!is_numeric($priceMin) ? NULL : $priceMin);
                    $priceMax         = (empty($priceMax) || $priceMax == 'blank') ? NULL : (!is_numeric($priceMax) ? NULL : $priceMax);

                    $stories_min  = (empty($stories_min) || $stories_min == 'blank') ? NULL : (!is_numeric($stories_min) ? NULL : $stories_min);
                    $stories_max  = (empty($stories_max) || $stories_max == 'blank') ? NULL : (!is_numeric($stories_max) ? NULL : $stories_max);

                    $buyerArr['bedroom_min']    = $bedroomMin;      $buyerArr['bedroom_max']    = $bedroomMax;
                    $buyerArr['bath_min']       = $bathMin;         $buyerArr['bath_max']       = $bathMax;
                    $buyerArr['size_min']       = $sizeMin;         $buyerArr['size_max']       = $sizeMax;
                    $buyerArr['lot_size_min']   = $lotSizeMin;      $buyerArr['lot_size_max']   = $lotSizeMax;
                    $buyerArr['build_year_min'] = $buildYearMin;    $buyerArr['build_year_max'] = $buildYearMax;
                    
                    $buyerArr['price_min']       = $priceMin;       $buyerArr['price_max']      = $priceMax;

                    $buyerArr['stories_min']      = $stories_min;       
                    $buyerArr['stories_max']      = $stories_max;


                    $propertyType = strtolower($this->modifiedString($row[18])); 
                    if(!empty($propertyType) && $propertyType != 'blank'){
                        $ptArr = $this->setMultiSelectValues($propertyType, 'property_type');
                        if(!empty($ptArr)){
                            $buyerArr['property_type'] = $ptArr;
                            
                            if(in_array(7,$buyerArr['property_type'])){
                                // set zoning value 
                                $buyerArr = $this->setMultiSelectValues($row, 'zoning', $buyerArr);
                                // set utilities value 
                                $buyerArr = $this->setSingleSelectValues($row[49], 'utilities', $buyerArr);
                                // set sewer value 
                                $buyerArr = $this->setSingleSelectValues($row[50], 'sewer', $buyerArr);
                            }

                            // set parking value 
                            $buyerArr = $this->setMultiSelectValues($row, 'parking', $buyerArr);
                        
                            // $buyerArr = $this->setSingleSelectValues($row[17], 'parking', $buyerArr);

                            // set propert flow value
                            $buyerArr = $this->setMultiSelectValues($row, 'property_flaw', $buyerArr);

                            // // set market_preferance value 
                            $buyerArr = $this->setSingleSelectValues($row[51], 'market_preferance', $buyerArr);

                            // // set contact_preferance value 
                            $buyerArr = $this->setSingleSelectValues($row[52], 'contact_preferance', $buyerArr);

                            // // set park value
                            $buyerArr = $this->setSingleSelectValues($row[54], 'park', $buyerArr);
                            

                            // set rooms value
                            $rooms        = strtolower($this->modifiedString($row[53])); 
                            $rooms        = (empty($rooms) || $rooms == 'blank') ? NULL : (!is_numeric($rooms) ? NULL : $rooms);
                            $buyerArr['rooms']       = $rooms;                                           

                            // set all radio button values
                            $buyerArr = $this->setRadioButtonValues($row, $buyerArr);
                            
                            $buyerType = strtolower($this->modifiedString($row[34])); 

                            if(!empty($buyerType) && $buyerType != 'blank'){
                                $btArr = $this->setMultiSelectValues($buyerType, 'buyer_type');
                                
                                if(!empty($btArr)){
                                    $buyerTypeArr = explode(',', $buyerType);
                                    $buyerTypeArr = array_map('trim',$buyerTypeArr);

                                    $buyerArr['buyer_type'] = $btArr[0];
                                    
                                    if(!in_array('creative', $buyerTypeArr) && !in_array('multi family buyer', $buyerTypeArr)){
                                        $this->setPurchaseMethod($row, $buyerArr);
                                    }

                                    if(in_array('creative', $buyerTypeArr)){
                                        $this->setCreativeBuyer($row, $buyerArr, $buyerTypeArr);
                                    } else if(in_array('multi family buyer', $buyerTypeArr)){
                                        $this->setMultiFamilyBuyer($row, $buyerArr);
                                    }                                                    
                                }
                            }
                        }
                    }
                        
                }
                        
            }
                
        }
        ++$this->rowCount;
    }

    private function setSingleSelectValues($value, $type,$buyerArr = []){
        if($type == 'utilities'){
            $utilitiesValues = config('constants.utilities');
            $utilitiesValues = array_map('strtolower',$utilitiesValues);
            $value = $this->modifiedString($value);
            $valueId = null;
            if(in_array($value, $utilitiesValues)){
                $valueId = array_search ($value, $utilitiesValues);
            }
            $buyerArr['utilities'] = $valueId;
            return $buyerArr;
        }else if($type == 'sewer'){
            $sewersValues = config('constants.sewers');
            $sewersValues = array_map('strtolower',$sewersValues);
            $value = strtolower($this->modifiedString($value));
            $valueId = null;
            if(in_array($value, $sewersValues)){
                $valueId = array_search ($value, $sewersValues);
            }
            $buyerArr['sewer'] = $valueId;
            return $buyerArr;
        }else if($type == 'market_preferance'){
            $marketPreferancesValues = config('constants.market_preferances');
            $marketPreferancesValues = array_map('strtolower',$marketPreferancesValues);
            $value = strtolower($this->modifiedString($value));
            $valueId = null;
            if(in_array($value, $marketPreferancesValues)){
                $valueId = array_search ($value, $marketPreferancesValues);
            }
            $buyerArr['market_preferance'] = $valueId;
            return $buyerArr;
        }else if($type == 'contact_preferance'){
            $contactPreferancesValues = config('constants.contact_preferances');
            $contactPreferancesValues = array_map('strtolower',$contactPreferancesValues);
            $value = strtolower($this->modifiedString($value));
            $valueId = null;
            if(in_array($value, $contactPreferancesValues)){
                $valueId = array_search ($value, $contactPreferancesValues);
            }
            $buyerArr['contact_preferance'] = $valueId;
            return $buyerArr;
        }else if($type == 'parking'){
            $parkingValues = config('constants.parking_values');
            $parkingValues = array_map('strtolower',$parkingValues);
            $value = strtolower($this->modifiedString($value));
            $valueId = null;
            if(in_array($value, $parkingValues)){
                $valueId = array_search ($value, $parkingValues);
            }
            $buyerArr['parking'] = $valueId;
            return $buyerArr;
        }
        else if($type == 'park'){
            $parkValues = config('constants.park');
            $parkValues = array_map('strtolower',$parkValues);
            $value = strtolower($this->modifiedString($value));
            $valueId = null;
            if(in_array($value, $parkValues)){
                $valueId = array_search ($value, $parkValues);
            }
            $buyerArr['park'] = $valueId;
            return $buyerArr;
        }
        

        
    }

    private function setMultiSelectValues($value, $type, $buyerArr = []){
        if($type == 'property_type'){
            $propertyTypeValues = config('constants.property_types');
            $propertyTypeValues = array_map('strtolower',$propertyTypeValues);
            $propertyTypeArr = explode(',', $value);
            $propertyTypeArr = array_map('trim', $propertyTypeArr);
            $ptArr = [];
            foreach($propertyTypeArr as $propertyTypeVal){
                if(in_array($propertyTypeVal, $propertyTypeValues)){
                    $ptKey = array_search ($propertyTypeVal, $propertyTypeValues);
                    $ptArr[] = $ptKey;
                }
            }
            return $ptArr;
        } else if($type == 'parking'){
            $parking = strtolower($this->modifiedString($value[17])); 
                                                
            if(empty($parking) || $parking == 'blank'){
                $parking = NULL;
            } else {
                $parkingValues = config('constants.parking_values');
                $parkingValues = array_map('strtolower',$parkingValues);
                $parkingArr = explode(',', $parking);
                $parkingArr = array_map('trim', $parkingArr);
                $prkArr = [];
                foreach($parkingArr as $parkingVal){
                    if(in_array($parkingVal, $parkingValues)){
                        $prkKey = array_search ($parkingVal, $parkingValues);
                        $prkArr[] = $prkKey;
                    }
                }
                if(empty($prkArr)){
                    $parking = NULL;
                } else {
                    $parking = $prkArr;
                }                                                    
            }
            $buyerArr['parking'] = $parking;
            return $buyerArr;
        } else if($type == 'property_flaw'){
            $propertyFlaw = strtolower($this->modifiedString($value[19]));
            if(empty($value) || $value == 'blank'){
                $propertyFlaw = NULL;
            } else {
                $propertyFlawValues = config('constants.property_flaws');
                $propertyFlawValues = array_map('strtolower',$propertyFlawValues);
                $propertyFlawArr = explode(',', $propertyFlaw);
                $propertyFlawArr = array_map('trim', $propertyFlawArr);
                $pfArr = [];
                foreach($propertyFlawArr as $propertyFlawVal){
                    if(in_array($propertyFlawVal, $propertyFlawValues)){
                        $pfKey = array_search ($propertyFlawVal, $propertyFlawValues);
                        $pfArr[] = $pfKey;
                    }
                }
                if(empty($pfArr)){
                    $propertyFlaw = NULL;
                } else {
                    $propertyFlaw = $pfArr;
                }
                $buyerArr['property_flaw'] = $propertyFlaw;
                return $buyerArr;
            }
        }else if($type == 'zoning'){
            $zoning = strtolower($this->modifiedString($value[48]));
            if(empty($value) || $value == 'blank'){
                $zoning = NULL;
            } else {
                $zoningValues = config('constants.zonings');
                $zoningValues = array_map('strtolower',$zoningValues);
                $zoningArr = explode(',', $zoning);
                $zoningArr = array_map('trim', $zoningArr);
                $pfArr = [];
                foreach($zoningArr as $zoningVal){
                    if(in_array($zoningVal, $zoningValues)){
                        $pfKey = array_search ($zoningVal, $zoningValues);
                        $pfArr[] = $pfKey;
                    }
                }
                if(empty($pfArr)){
                    $zoning = NULL;
                } else {
                    $zoning = $pfArr;
                }
                $buyerArr['zoning'] = $zoning;
                return $buyerArr;
            }
        }else if($type == 'buyer_type'){
            $buyerTypeValues = config('constants.buyer_types');
            $buyerTypeValues = array_map('strtolower',$buyerTypeValues);
            $buyerTypeArr = explode(',', $value);
            $buyerTypeArr = array_map('trim',$buyerTypeArr);
            $btArr = [];
            foreach($buyerTypeArr as $buyerTypeVal){
                if(in_array($buyerTypeVal, $buyerTypeValues)){
                    $btKey = array_search ($buyerTypeVal, $buyerTypeValues);
                    $btArr[] = $btKey;
                }
            }
            return $btArr;
        }        
    }

    private function setRadioButtonValues($row, $buyerArr){

        $squatters = strtolower($this->modifiedString($row[55]));
        $squatters = (($squatters == 'yes') ? 1 : (($squatters == 'no') ? 0 : NULL));
        $buyerArr['squatters'] = $squatters;

        $permanent_affix = strtolower($this->modifiedString($row[56])); 
        $permanent_affix = (($permanent_affix == 'yes') ? 1 : (($permanent_affix == 'no') ? 0 : 0));
        $buyerArr['permanent_affix'] = $permanent_affix;

        $solar = strtolower($this->modifiedString($row[20])); 
        $solar = (($solar == 'yes') ? 1 : (($solar == 'no') ? 0 : NULL));
        $buyerArr['solar'] = $solar;
        
        $pool = strtolower($this->modifiedString($row[21])); 
        $pool = (($pool == 'yes') ? 1 : (($pool == 'no') ? 0 : NULL));
        $buyerArr['pool'] = $pool;
        
        $septic = strtolower($this->modifiedString($row[22])); 
        $septic = (($septic == 'yes') ? 1 : (($septic == 'no') ? 0 : NULL));
        $buyerArr['septic'] = $septic;
        
        $well = strtolower($this->modifiedString($row[23]));
        $well = (($well == 'yes') ? 1 : (($well == 'no') ? 0 : NULL));
        $buyerArr['well'] = $well;
        
        $ageRestriction = strtolower($this->modifiedString($row[24]));
        $ageRestriction = (($ageRestriction == 'yes') ? 1 : (($ageRestriction == 'no') ? 0 : NULL));
        $buyerArr['age_restriction'] = $ageRestriction;
        
        $rentalRestriction = strtolower($this->modifiedString($row[25]));
        $rentalRestriction = (($rentalRestriction == 'yes') ? 1 : (($rentalRestriction == 'no') ? 0 : NULL));
        $buyerArr['rental_restriction'] = $rentalRestriction;
        
        $hoa = strtolower($this->modifiedString($row[26]));
        $hoa = (($hoa == 'yes') ? 1 : (($hoa == 'no') ? 0 : NULL));
        $buyerArr['hoa'] = $hoa;
        
        $tenant = strtolower($this->modifiedString($row[27]));
        $tenant = (($tenant == 'yes') ? 1 : (($tenant == 'no') ? 0 : NULL));
        $buyerArr['tenant'] = $tenant;

        $postPossession = strtolower($this->modifiedString($row[28]));
        $postPossession = (($postPossession == 'yes') ? 1 : (($postPossession == 'no') ? 0 : NULL));
        $buyerArr['post_possession'] = $postPossession;
        
        $buildingRequired = strtolower($this->modifiedString($row[29]));
        $buildingRequired = (($buildingRequired == 'yes') ? 1 : (($buildingRequired == 'no') ? 0 : NULL));
        $buyerArr['building_required'] = $buildingRequired;
        
        $foundationIssues = strtolower($this->modifiedString($row[30]));
        $foundationIssues = (($foundationIssues == 'yes') ? 1 : (($foundationIssues == 'no') ? 0 : NULL));
        $buyerArr['foundation_issues'] = $foundationIssues;
        
        $mold = strtolower($this->modifiedString($row[31]));
        $mold = (($mold == 'yes') ? 1 : (($mold == 'no') ? 0 : NULL));
        $buyerArr['mold'] = $mold;
        
        $fireDamaged = strtolower($this->modifiedString($row[32]));
        $fireDamaged = (($fireDamaged == 'yes') ? 1 : (($fireDamaged == 'no') ? 0 : NULL));
        $buyerArr['fire_damaged'] = $fireDamaged;
        
        $rebuild = strtolower($this->modifiedString($row[33]));
        $rebuild = (($rebuild == 'yes') ? 1 : (($rebuild == 'no') ? 0 : NULL));
        $buyerArr['rebuild'] = $rebuild;
        
        return $buyerArr;
    }

    private function setCreativeBuyer($row, $buyerArr, $buyerTypeArr){
        $maxDownPaymentPercentage = strtolower($this->modifiedString($row[35]));
        $maxDownPaymentMoney = strtolower($this->modifiedString($row[36]));
        $maxInterestRate = strtolower($this->modifiedString($row[37])); 
        $balloonPayment = strtolower($this->modifiedString($row[38]));
        if(!empty($maxDownPaymentPercentage) && $maxDownPaymentPercentage != 'blank' && is_numeric($maxDownPaymentPercentage)){
            if(!empty($maxInterestRate) && $maxInterestRate != 'blank' && is_numeric($maxInterestRate)){
                if(!empty($balloonPayment) && $balloonPayment != 'blank' && ($balloonPayment == 'yes' || $balloonPayment == 'no')){                                                                        
                    if(empty($maxDownPaymentMoney) || $maxDownPaymentMoney == 'blank' || !is_numeric($maxDownPaymentMoney)){
                        $maxDownPaymentMoney = NULL;
                    }

                    $buyerArr['max_down_payment_percentage'] = $maxDownPaymentPercentage;
                    $buyerArr['max_down_payment_money'] = $maxDownPaymentMoney;
                    $buyerArr['max_interest_rate'] = $maxInterestRate;
                    $buyerArr['balloon_payment'] = $balloonPayment == 'yes' ? 1 : 0;
                    
                    if(in_array('multi family buyer', $buyerTypeArr)){
                        $this->setMultiFamilyBuyer($row, $buyerArr);
                    } else {
                        $this->setPurchaseMethod($row, $buyerArr);
                    }
                }
            }
        }
    }

    private function setMultiFamilyBuyer($row, $buyerArr){
        $unitMin = strtolower($this->modifiedString($row[39]));
        $unitMax = strtolower($this->modifiedString($row[40]));
        $buildingClass = strtolower($this->modifiedString($row[41])); 
        $valueAdd = strtolower($this->modifiedString($row[42]));

        if(!empty($unitMin) && !empty($unitMax) && !empty($buildingClass) && is_numeric($unitMin) && is_numeric($unitMax) && ($valueAdd == 'yes' || $valueAdd == 'no')){
            $buildingClassValues = config('constants.building_class_values');
            $buildingClassValues = array_map('strtolower',$buildingClassValues);
            $buildingClassValArr = explode(',', $buildingClass);
            $buildingClassValArr = array_map('trim', $buildingClassValArr);
            $buildingClassArr = [];
            foreach($buildingClassValArr as $buildingClassVal){
                if(in_array($buildingClassVal, $buildingClassValues)){
                    $buildingClassKey = array_search ($buildingClassVal, $buildingClassValues);
                    $buildingClassArr[] = $buildingClassKey;
                }
            }
            if(!empty($buildingClassArr)){
                $buildingClass = $buildingClassArr;

                $buyerArr['unit_min'] = $unitMin;
                $buyerArr['unit_max'] = $unitMax;
                $buyerArr['building_class'] = $buildingClass;
                $buyerArr['value_add'] = $valueAdd == 'yes' ? 1 : 0;
                
                $this->setPurchaseMethod($row, $buyerArr);
            }
        }
    }

    private function setPurchaseMethod($row, $buyerArr){
        $purchaseMethod = strtolower($this->modifiedString($row[43]));
        if(!empty($purchaseMethod) && $purchaseMethod != 'blank'){
            $purchaseMethodValues = config('constants.purchase_methods');
            $purchaseMethodValues = array_map('strtolower',$purchaseMethodValues);
            $purchaseMethodArr = explode(',', $purchaseMethod);
            $purchaseMethodArr = array_map('trim', $purchaseMethodArr);
            $pmArr = [];
            foreach($purchaseMethodArr as $purchaseMethodVal){
                if(in_array($purchaseMethodVal, $purchaseMethodValues)){
                    $pmKey = array_search ($purchaseMethodVal, $purchaseMethodValues);
                    $pmArr[] = $pmKey;
                }
            }
            if(!empty($pmArr)){
                $purchaseMethod = $pmArr;
                $buyerArr['purchase_method'] = $purchaseMethod;
                
                $this->manageBuyer($buyerArr);

                return true;
            }
        }
    }

    private function modifiedString($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function manageBuyer(array $buyerArr)
    {
        $rowIndex = $this->rowCount;

        try {
            DB::beginTransaction();
            $userDetails =  [
                'first_name'     => $buyerArr['first_name'],
                'last_name'      => $buyerArr['last_name'],
                'name'           => ucwords($buyerArr['first_name'].' '.$buyerArr['last_name']),
                'email'          => $buyerArr['email'],
                'country_code'   => $buyerArr['country_code'], 
                'phone'          => $buyerArr['phone'], 
            ];

            $existingUser = User::withTrashed()->where('email', $buyerArr['email'])->first();

            if ($existingUser) {
                if ($existingUser->trashed()) {
                    $this->softDeletedCount++;
                    $this->softDeletedIndices[] = $rowIndex;
                }else{
                    // User exists: Only create buyer's records
                    $this->createBuyerRecords($existingUser, $userDetails, $buyerArr);
                    ++$this->insertedCount;
                }
            } else {
                // User doesn't exist: Create user and associated buyer's records
                $this->createUserAndBuyerRecords($userDetails, $buyerArr);
                ++$this->insertedCount;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            ++$this->skippedCount;

            // Log skipped rows due to errors
            $logMessage = "Row Index " . ($rowIndex) . ": Error: ".$e->getMessage()."->".$e->getLine();
            $this->skippedErrors[] = $logMessage;
        }
    }

    private function createUserAndBuyerRecords(array $userDetails, array $buyerArr)
    {
        $createUser = User::create($userDetails);

        if ($createUser) {
            // Create buyer verification entry
            $createUser->buyerVerification()->create(['user_id' => $createUser->id]);

            // Assign buyer role
            $createUser->roles()->sync(config('constants.roles.buyer'));

            // Create buyer details
            $buyerArr['buyer_user_id'] = $createUser->id;
            $buyerArr = collect($buyerArr)->except(['first_name', 'last_name', 'email', 'country_code','phone'])->all();

            // Log::info($buyerArr);
            
            $createUser->buyerDetail()->create($buyerArr);

            // Check if buyer details were created successfully
            if ($createUser->buyerDetail) {
                // Purchased buyer
                $syncData = [
                    'buyer_id'   => $createUser->buyerDetail->id,
                    'created_at' => Carbon::now(),
                ];
                auth()->user()->purchasedBuyers()->create($syncData);
            }

            // Send verification email
            $createUser->NotificationSendToBuyerVerifyEmail();

            return $createUser;
        }

        return null;
    }

    private function createBuyerRecords(User $existingUser, array $userDetails, array $buyerArr)
    {
        $existingUser->update($userDetails);

        $buyerArr['buyer_user_id'] = $existingUser->id;
        $buyerArr = collect($buyerArr)->except(['first_name', 'last_name', 'email', 'country_code','phone'])->all();
        $createdBuyer = $existingUser->buyerDetail()->create($buyerArr);

        if ($createdBuyer) {
            // Purchased buyer
            $syncData = [
                'buyer_id'   => $createdBuyer->id,
                'created_at' => Carbon::now(),
            ];
            auth()->user()->purchasedBuyers()->create($syncData);
        }

        return $existingUser;
    }
}
