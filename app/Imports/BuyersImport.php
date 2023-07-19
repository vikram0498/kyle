<?php

namespace App\Imports;

use App\Models\Buyer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

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
        if($row[0] != 'First Name'){
            // dd($row);
            $buyerArr = [];
            $fName = $this->modifiedString($row[0]); $lName = $this->modifiedString($row[1]);
            if(!empty($fName) && !empty($lName)){
                $buyerArr['user_id'] = auth()->user()->id;
                $buyerArr['first_name'] = $fName;
                $buyerArr['last_name'] = $lName;
                $buyerArr['name'] = $fName.' '.$lName;
                $email = $this->modifiedString($row[2]);
                if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) ) {
                    $buyerExists = Buyer::where('email', $email)->exists();
                    if(!$buyerExists){
                        $buyerArr['email'] = $email;
                        $phone = $this->modifiedString($row[3]);
                        if(!empty($phone) && is_numeric($phone)){
                            $buyerArr['phone'] = $phone;
                            $address = $this->modifiedString($row[4]); $city = $this->modifiedString($row[5]); $state = $this->modifiedString($row[6]); $zipCode = $this->modifiedString($row[7]);
                            if(!empty($address) && !empty($city) && !empty($state) && !empty($zipCode)){
                                $buyerArr['address'] = $address;
                                $buyerArr['city'] = $city;
                                $buyerArr['state'] = $state;
                                $buyerArr['zip_code'] = $zipCode;                                

                                $companyName = strtolower($this->modifiedString($row[8]));
                                $companyName = (empty($companyName) || $companyName == 'blank') ? NULL : $companyName;
                                $buyerArr['company_name'] = $companyName;

                                $occupation = strtolower($this->modifiedString($row[9]));
                                $companyName = (empty($occupation) || $occupation == 'blank') ? NULL : $occupation;
                                $buyerArr['occupation'] = $occupation;

                                $replacingOccupation = strtolower($this->modifiedString($row[10]));
                                $replacingOccupation = (empty($replacingOccupation) || $replacingOccupation == 'blank') ? NULL : $replacingOccupation;
                                $buyerArr['replacing_occupation'] = $replacingOccupation;

                                // number values min and max
                                $bedroomMin     = strtolower($this->modifiedString($row[11]));      $bedroomMax      = strtolower($this->modifiedString($row[12]));
                                $bathMin        = strtolower($this->modifiedString($row[13]));      $bathMax         = strtolower($this->modifiedString($row[14]));
                                $sizeMin        = strtolower($this->modifiedString($row[15]));      $sizeMax         = strtolower($this->modifiedString($row[16]));
                                $lotSizeMin     = strtolower($this->modifiedString($row[17]));      $lotSizeMax      = strtolower($this->modifiedString($row[18]));
                                $buildYearMin   = strtolower($this->modifiedString($row[19]));      $buildYearMax    = strtolower($this->modifiedString($row[20]));
                                $arvMin         = strtolower($this->modifiedString($row[21]));      $arvMax          = strtolower($this->modifiedString($row[22]));

                                if(!empty($bedroomMin) && !empty($bedroomMax) && !empty($sizeMin) && !empty($sizeMax) ){
                                    if(is_numeric($bedroomMin) && is_numeric($bedroomMax) && is_numeric($sizeMin) && is_numeric($sizeMax)){

                                        $bathMin        = (empty($bathMax) || $bathMin == 'blank') ? NULL : (!is_numeric($bathMin) ? NULL : $bathMin);
                                        $bathMax        = (empty($bathMax) || $bathMax == 'blank') ? NULL : (!is_numeric($bathMax) ? NULL : $bathMax);

                                        $lotSizeMin     = (empty($lotSizeMin) || $lotSizeMin == 'blank') ? NULL : (!is_numeric($lotSizeMin) ? NULL : $lotSizeMin);
                                        $lotSizeMax     = (empty($lotSizeMax) || $lotSizeMax == 'blank') ? NULL : (!is_numeric($lotSizeMax) ? NULL : $lotSizeMax);

                                        $buildYearMin   = (empty($buildYearMin) || $buildYearMin == 'blank') ? NULL : (!is_numeric($buildYearMin) ? NULL : $buildYearMin);
                                        $buildYearMax   = (empty($buildYearMax) || $buildYearMax == 'blank') ? NULL : (!is_numeric($buildYearMax) ? NULL : $buildYearMax);
                                        
                                        $arvMin         = (empty($arvMin) || $arvMin == 'blank') ? NULL : (!is_numeric($arvMin) ? NULL : $arvMin);
                                        $arvMax         = (empty($arvMax) || $arvMax == 'blank') ? NULL : (!is_numeric($arvMax) ? NULL : $arvMax);

                                        $buyerArr['bedroom_min']    = $bedroomMin;      $buyerArr['bedroom_max']    = $bedroomMax;
                                        $buyerArr['bath_min']       = $bathMin;         $buyerArr['bath_max']       = $bathMax;
                                        $buyerArr['size_min']       = $sizeMin;         $buyerArr['size_max']       = $sizeMax;
                                        $buyerArr['lot_size_min']   = $lotSizeMin;      $buyerArr['lot_size_max']   = $lotSizeMax;
                                        $buyerArr['build_year_min'] = $buildYearMin;    $buyerArr['build_year_max'] = $buildYearMax;
                                        $buyerArr['arv_min']        = $arvMin;          $buyerArr['arv_max']        = $arvMax;

                                        $propertyType = strtolower($this->modifiedString($row[24])); 
                                        if(!empty($propertyType) && $propertyType != 'blank'){
                                            $ptArr = $this->setMultiSelectValues($propertyType, 'property_type');
                                            if(!empty($ptArr)){
                                                $buyerArr['property_type'] = $ptArr;
                                                
                                                // set parking value 
                                                $buyerArr = $this->setMultiSelectValues($row, 'parking', $buyerArr);

                                                // set propert flow value
                                                $buyerArr = $this->setMultiSelectValues($row, 'property_flaw', $buyerArr);
                                                
                                                // set all radio button values
                                                $buyerArr = $this->setRadioButtonValues($row, $buyerArr);

                                                $buyerType = strtolower($this->modifiedString($row[40])); 
                                                if(!empty($buyerType) && $buyerType != 'blank'){
                                                    $btArr = $this->setMultiSelectValues($buyerType, 'buyer_type');
                                                    
                                                    if(!empty($btArr)){
                                                        $buyerTypeArr = explode(',', $buyerType);
                                                        $buyerTypeArr = array_map('trim',$buyerTypeArr);

                                                        $buyerArr['buyer_type'] = $btArr;
                                                        
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
                    }
                }
            }
        }
        ++$this->rowCount;
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
            $parking = strtolower($this->modifiedString($value[23])); 
                                                
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
            $propertyFlaw = strtolower($this->modifiedString($value[25]));
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
        } else if($type == 'buyer_type'){
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
        $solar = strtolower($this->modifiedString($row[26])); 
        $solar = (($solar == 'yes') ? 1 : (($solar == 'no') ? 0 : NULL));
        $buyerArr['solar'] = $solar;
        
        $pool = strtolower($this->modifiedString($row[27])); 
        $pool = (($pool == 'yes') ? 1 : (($pool == 'no') ? 0 : NULL));
        $buyerArr['pool'] = $pool;
        
        $septic = strtolower($this->modifiedString($row[28])); 
        $septic = (($septic == 'yes') ? 1 : (($septic == 'no') ? 0 : NULL));
        $buyerArr['septic'] = $septic;
        
        $well = strtolower($this->modifiedString($row[29]));
        $well = (($well == 'yes') ? 1 : (($well == 'no') ? 0 : NULL));
        $buyerArr['well'] = $well;
        
        $ageRestriction = strtolower($this->modifiedString($row[30]));
        $ageRestriction = (($ageRestriction == 'yes') ? 1 : (($ageRestriction == 'no') ? 0 : NULL));
        $buyerArr['age_restriction'] = $ageRestriction;
        
        $rentalRestriction = strtolower($this->modifiedString($row[31]));
        $rentalRestriction = (($rentalRestriction == 'yes') ? 1 : (($rentalRestriction == 'no') ? 0 : NULL));
        $buyerArr['rental_restriction'] = $rentalRestriction;
        
        $hoa = strtolower($this->modifiedString($row[32]));
        $hoa = (($hoa == 'yes') ? 1 : (($hoa == 'no') ? 0 : NULL));
        $buyerArr['hoa'] = $hoa;
        
        $tenant = strtolower($this->modifiedString($row[33]));
        $tenant = (($tenant == 'yes') ? 1 : (($tenant == 'no') ? 0 : NULL));
        $buyerArr['tenant'] = $tenant;
        
        $postPossession = strtolower($this->modifiedString($row[34]));
        $postPossession = (($postPossession == 'yes') ? 1 : (($postPossession == 'no') ? 0 : NULL));
        $buyerArr['post_possession'] = $postPossession;
        
        $buildingRequired = strtolower($this->modifiedString($row[35]));
        $buildingRequired = (($buildingRequired == 'yes') ? 1 : (($buildingRequired == 'no') ? 0 : NULL));
        $buyerArr['building_required'] = $buildingRequired;
        
        $foundationIssues = strtolower($this->modifiedString($row[36]));
        $foundationIssues = (($foundationIssues == 'yes') ? 1 : (($foundationIssues == 'no') ? 0 : NULL));
        $buyerArr['foundation_issues'] = $foundationIssues;
        
        $mold = strtolower($this->modifiedString($row[37]));
        $mold = (($mold == 'yes') ? 1 : (($mold == 'no') ? 0 : NULL));
        $buyerArr['mold'] = $mold;
        
        $fireDamaged = strtolower($this->modifiedString($row[38]));
        $fireDamaged = (($fireDamaged == 'yes') ? 1 : (($fireDamaged == 'no') ? 0 : NULL));
        $buyerArr['fire_damaged'] = $fireDamaged;
        
        $rebuild = strtolower($this->modifiedString($row[39]));
        $rebuild = (($rebuild == 'yes') ? 1 : (($rebuild == 'no') ? 0 : NULL));
        $buyerArr['rebuild'] = $rebuild;
        
        return $buyerArr;
    }

    private function setCreativeBuyer($row, $buyerArr, $buyerTypeArr){
        $maxDownPaymentPercentage = strtolower($this->modifiedString($row[41]));
        $maxDownPaymentMoney = strtolower($this->modifiedString($row[42]));
        $maxInterestRate = strtolower($this->modifiedString($row[43])); 
        $balloonPayment = strtolower($this->modifiedString($row[44]));
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
        $unitMin = strtolower($this->modifiedString($row[45]));
        $unitMax = strtolower($this->modifiedString($row[46]));
        $buildingClass = strtolower($this->modifiedString($row[47])); 
        $valueAdd = strtolower($this->modifiedString($row[48]));

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
        $purchaseMethod = strtolower($this->modifiedString($row[49]));
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
                return Buyer::create($buyerArr);
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
