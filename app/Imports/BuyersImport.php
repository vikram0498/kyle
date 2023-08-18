<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Buyer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\DB; 
use Collection;

class BuyersImport implements ToModel, WithStartRow
{
    private $rowCount = 0;
    private $insertedCount = 0;

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
       
        $buyerArr = [];
        $fName = $this->modifiedString($row[0]); $lName = $this->modifiedString($row[1]);
        if(!empty($fName) && !empty($lName)){
            $buyerArr['user_id'] = 20;
            $buyerArr['first_name'] = $fName;
            $buyerArr['last_name'] = $lName;
            $buyerArr['name'] = $fName.' '.$lName;
            $email = $this->modifiedString($row[2]);
            if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) ) {
                // $buyerExists = Buyer::where('email', $email)->exists();
                // if(!$buyerExists){
                    $buyerArr['email'] = $email;
                    $phone = $this->modifiedString($row[3]);
                    if(!empty($phone) && is_numeric($phone)){
                        $buyerArr['phone'] = $phone;
                        $address = $this->modifiedString($row[4]); 
                        
                        $country = $this->modifiedString($row[5]); 
                        $city = $this->modifiedString($row[6]); 
                        $state = $this->modifiedString($row[7]); 

                        $countryId = 0;
                        $stateId = 0;
                        $cityId = 0;
                        
                        $countryData = DB::table('countries')->where('name', $country)->first();
                        if(!empty($countryData)){
                            $countryId = $countryData->id;

                            $stateData = DB::table('states')->where('country_id', $countryId)->where('name', $state)->first();
                            if(!empty($stateData)){
                                $stateId = $stateData->id;

                                $cityData = DB::table('cities')->where('state_id', $stateId)->where('name', $city)->first();
                                if(!empty($cityData)){
                                    $cityId = $cityData->id;
                                }
                            }
                        }                            

                        $zipCode = $this->modifiedString($row[8]);

                        // if(!empty($address) && !empty($country) && !empty($city) && !empty($state) && !empty($zipCode) && $countryId > 0 && $stateId > 0 && $cityId > 0){
                        if(!empty($address) && !empty($country) && !empty($zipCode) && $countryId > 0){
                            $buyerArr['address'] = $address;
                            $buyerArr['country'] = $country;
                            $buyerArr['city'] = $cityId > 0 ? $city : NULL;
                            $buyerArr['state'] = $stateId > 0 ? $state : NULL;
                            $buyerArr['zip_code'] = $zipCode;                                

                            $companyName = strtolower($this->modifiedString($row[9]));
                            $companyName = (empty($companyName) || $companyName == 'blank') ? NULL : $companyName;
                            $buyerArr['company_name'] = $companyName;

                            $occupation = strtolower($this->modifiedString($row[10]));
                            $companyName = (empty($occupation) || $occupation == 'blank') ? NULL : $occupation;
                            $buyerArr['occupation'] = $occupation;

                            $replacingOccupation = strtolower($this->modifiedString($row[11]));
                            $replacingOccupation = (empty($replacingOccupation) || $replacingOccupation == 'blank') ? NULL : $replacingOccupation;
                            $buyerArr['replacing_occupation'] = $replacingOccupation;

                            // number values min and max
                            $bedroomMin     = strtolower($this->modifiedString($row[12]));      $bedroomMax      = strtolower($this->modifiedString($row[13]));
                            $bathMin        = strtolower($this->modifiedString($row[14]));      $bathMax         = strtolower($this->modifiedString($row[15]));
                            $sizeMin        = strtolower($this->modifiedString($row[16]));      $sizeMax         = strtolower($this->modifiedString($row[17]));
                            $lotSizeMin     = strtolower($this->modifiedString($row[18]));      $lotSizeMax      = strtolower($this->modifiedString($row[19]));
                            $buildYearMin   = strtolower($this->modifiedString($row[20]));      $buildYearMax    = strtolower($this->modifiedString($row[21]));
                            $arvMin         = strtolower($this->modifiedString($row[22]));      $arvMax          = strtolower($this->modifiedString($row[23]));

                            $priceMin         = strtolower($this->modifiedString($row[51]));      $priceMax          = strtolower($this->modifiedString($row[52]));

                            $of_stories_min         = strtolower($this->modifiedString($row[53]));      $of_stories_max          = strtolower($this->modifiedString($row[54]));

                            if(!empty($bedroomMin) && !empty($bedroomMax) && !empty($sizeMin) && !empty($sizeMax) && !empty($priceMin) && !empty($priceMax) && !empty($of_stories_min) && !empty($of_stories_min)){
                                if(is_numeric($bedroomMin) && is_numeric($bedroomMax) && is_numeric($sizeMin) && is_numeric($sizeMax) && is_numeric($priceMin) && is_numeric($priceMax) && is_numeric($of_stories_min) && is_numeric($of_stories_max)){

                                    $bathMin        = (empty($bathMax) || $bathMin == 'blank') ? NULL : (!is_numeric($bathMin) ? NULL : $bathMin);
                                    $bathMax        = (empty($bathMax) || $bathMax == 'blank') ? NULL : (!is_numeric($bathMax) ? NULL : $bathMax);

                                    $lotSizeMin     = (empty($lotSizeMin) || $lotSizeMin == 'blank') ? NULL : (!is_numeric($lotSizeMin) ? NULL : $lotSizeMin);
                                    $lotSizeMax     = (empty($lotSizeMax) || $lotSizeMax == 'blank') ? NULL : (!is_numeric($lotSizeMax) ? NULL : $lotSizeMax);

                                    $buildYearMin   = (empty($buildYearMin) || $buildYearMin == 'blank') ? NULL : (!is_numeric($buildYearMin) ? NULL : $buildYearMin);
                                    $buildYearMax   = (empty($buildYearMax) || $buildYearMax == 'blank') ? NULL : (!is_numeric($buildYearMax) ? NULL : $buildYearMax);
                                    
                                    $arvMin         = (empty($arvMin) || $arvMin == 'blank') ? NULL : (!is_numeric($arvMin) ? NULL : $arvMin);
                                    $arvMax         = (empty($arvMax) || $arvMax == 'blank') ? NULL : (!is_numeric($arvMax) ? NULL : $arvMax);

                                    $priceMin         = (empty($priceMin) || $priceMin == 'blank') ? NULL : (!is_numeric($priceMin) ? NULL : $priceMin);
                                    $priceMax         = (empty($priceMax) || $priceMax == 'blank') ? NULL : (!is_numeric($priceMax) ? NULL : $priceMax);

                                    $of_stories_min  = (empty($of_stories_min) || $of_stories_min == 'blank') ? NULL : (!is_numeric($of_stories_min) ? NULL : $of_stories_min);

                                    $of_stories_max  = (empty($of_stories_max) || $of_stories_max == 'blank') ? NULL : (!is_numeric($of_stories_max) ? NULL : $of_stories_max);

                                    $buyerArr['bedroom_min']    = $bedroomMin;      $buyerArr['bedroom_max']    = $bedroomMax;
                                    $buyerArr['bath_min']       = $bathMin;         $buyerArr['bath_max']       = $bathMax;
                                    $buyerArr['size_min']       = $sizeMin;         $buyerArr['size_max']       = $sizeMax;
                                    $buyerArr['lot_size_min']   = $lotSizeMin;      $buyerArr['lot_size_max']   = $lotSizeMax;
                                    $buyerArr['build_year_min'] = $buildYearMin;    $buyerArr['build_year_max'] = $buildYearMax;
                                    $buyerArr['arv_min']        = $arvMin;          $buyerArr['arv_max']        = $arvMax;

                                    $buyerArr['price_min']       = $priceMin;       $buyerArr['price_max']      = $priceMax;

                                    $buyerArr['of_stories_min']      = $of_stories_min;       
                                    $buyerArr['of_stories_max']      = $of_stories_max;

                                    $propertyType = strtolower($this->modifiedString($row[25])); 
                                    if(!empty($propertyType) && $propertyType != 'blank'){
                                        $ptArr = $this->setMultiSelectValues($propertyType, 'property_type');
                                        if(!empty($ptArr)){
                                            $buyerArr['property_type'] = $ptArr;
                                            
                                            if(in_array(7,$buyerArr['property_type'])){
                                                // set zoning value 
                                                $buyerArr = $this->setMultiSelectValues($row, 'zoning', $buyerArr);

                                                // set utilities value 
                                                $buyerArr = $this->setSingleSelectValues($row[56], 'utilities', $buyerArr);

                                                // set sewer value 
                                                $buyerArr = $this->setSingleSelectValues($row[57], 'sewer', $buyerArr);
                                            }

                                            // set parking value 
                                            // $buyerArr = $this->setMultiSelectValues($row, 'parking', $buyerArr);
                                        
                                            $buyerArr = $this->setSingleSelectValues($row[24], 'parking', $buyerArr);

                                            // set propert flow value
                                            $buyerArr = $this->setMultiSelectValues($row, 'property_flaw', $buyerArr);

                                            // // set market_preferance value 
                                            $buyerArr = $this->setSingleSelectValues($row[58], 'market_preferance', $buyerArr);

                                            // // set contact_preferance value 
                                            $buyerArr = $this->setSingleSelectValues($row[59], 'contact_preferance', $buyerArr);

                                            // set all radio button values
                                            $buyerArr = $this->setRadioButtonValues($row, $buyerArr);

                                            $buyerType = strtolower($this->modifiedString($row[41])); 
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
                    }
                // }
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
            $parking = strtolower($this->modifiedString($value[24])); 
                                                
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
            $propertyFlaw = strtolower($this->modifiedString($value[26]));
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
            $zoning = strtolower($this->modifiedString($value[55]));
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
        $solar = strtolower($this->modifiedString($row[27])); 
        $solar = (($solar == 'yes') ? 1 : (($solar == 'no') ? 0 : NULL));
        $buyerArr['solar'] = $solar;
        
        $pool = strtolower($this->modifiedString($row[27])); 
        $pool = (($pool == 'yes') ? 1 : (($pool == 'no') ? 0 : NULL));
        $buyerArr['pool'] = $pool;
        
        $septic = strtolower($this->modifiedString($row[29])); 
        $septic = (($septic == 'yes') ? 1 : (($septic == 'no') ? 0 : NULL));
        $buyerArr['septic'] = $septic;
        
        $well = strtolower($this->modifiedString($row[30]));
        $well = (($well == 'yes') ? 1 : (($well == 'no') ? 0 : NULL));
        $buyerArr['well'] = $well;
        
        $ageRestriction = strtolower($this->modifiedString($row[31]));
        $ageRestriction = (($ageRestriction == 'yes') ? 1 : (($ageRestriction == 'no') ? 0 : NULL));
        $buyerArr['age_restriction'] = $ageRestriction;
        
        $rentalRestriction = strtolower($this->modifiedString($row[32]));
        $rentalRestriction = (($rentalRestriction == 'yes') ? 1 : (($rentalRestriction == 'no') ? 0 : NULL));
        $buyerArr['rental_restriction'] = $rentalRestriction;
        
        $hoa = strtolower($this->modifiedString($row[33]));
        $hoa = (($hoa == 'yes') ? 1 : (($hoa == 'no') ? 0 : NULL));
        $buyerArr['hoa'] = $hoa;
        
        $tenant = strtolower($this->modifiedString($row[34]));
        $tenant = (($tenant == 'yes') ? 1 : (($tenant == 'no') ? 0 : NULL));
        $buyerArr['tenant'] = $tenant;
        
        $postPossession = strtolower($this->modifiedString($row[35]));
        $postPossession = (($postPossession == 'yes') ? 1 : (($postPossession == 'no') ? 0 : NULL));
        $buyerArr['post_possession'] = $postPossession;
        
        $buildingRequired = strtolower($this->modifiedString($row[36]));
        $buildingRequired = (($buildingRequired == 'yes') ? 1 : (($buildingRequired == 'no') ? 0 : NULL));
        $buyerArr['building_required'] = $buildingRequired;
        
        $foundationIssues = strtolower($this->modifiedString($row[37]));
        $foundationIssues = (($foundationIssues == 'yes') ? 1 : (($foundationIssues == 'no') ? 0 : NULL));
        $buyerArr['foundation_issues'] = $foundationIssues;
        
        $mold = strtolower($this->modifiedString($row[38]));
        $mold = (($mold == 'yes') ? 1 : (($mold == 'no') ? 0 : NULL));
        $buyerArr['mold'] = $mold;
        
        $fireDamaged = strtolower($this->modifiedString($row[39]));
        $fireDamaged = (($fireDamaged == 'yes') ? 1 : (($fireDamaged == 'no') ? 0 : NULL));
        $buyerArr['fire_damaged'] = $fireDamaged;
        
        $rebuild = strtolower($this->modifiedString($row[40]));
        $rebuild = (($rebuild == 'yes') ? 1 : (($rebuild == 'no') ? 0 : NULL));
        $buyerArr['rebuild'] = $rebuild;
        
        return $buyerArr;
    }

    private function setCreativeBuyer($row, $buyerArr, $buyerTypeArr){
        $maxDownPaymentPercentage = strtolower($this->modifiedString($row[42]));
        $maxDownPaymentMoney = strtolower($this->modifiedString($row[43]));
        $maxInterestRate = strtolower($this->modifiedString($row[44])); 
        $balloonPayment = strtolower($this->modifiedString($row[45]));
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
        $unitMin = strtolower($this->modifiedString($row[46]));
        $unitMax = strtolower($this->modifiedString($row[47]));
        $buildingClass = strtolower($this->modifiedString($row[48])); 
        $valueAdd = strtolower($this->modifiedString($row[49]));

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
        $purchaseMethod = strtolower($this->modifiedString($row[50]));
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
                $this->insertedCount++;
               
                $createdBuyer = Buyer::create($buyerArr);

                if(auth()->user()->is_seller){
                    //Purchased buyer
                    $syncData['buyer_id'] = $createdBuyer->id;
                    $syncData['created_at'] = Carbon::now();

                    auth()->user()->purchasedBuyers()->create($syncData);
                }
                
                return $createdBuyer;
                
            }
        }
    }

    private function modifiedString($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }

    public function totalRowCount(): int
    {
        return $this->rowCount;
    }

    public function insertedCount(): int
    {
        return $this->insertedCount;
    }
}
