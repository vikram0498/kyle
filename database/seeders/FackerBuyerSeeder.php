<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class FackerBuyerSeeder extends Seeder
{
    public function run()
    {
        
        // Buyer
        foreach (range(1, 150) as $index){
            $firstName = fake('en_US')->firstName();
            $lastName = fake('en_US')->lastName();

            $uniqueSafeEmail = fake('en_US')->unique()->safeEmail; 

            $phoneNumber = str_replace(['-', '(', ')', ',',' '], '', fake('en_US')->phoneNumber());
          
            // Start create users table
            $userDetails =  [
                'first_name'     => $firstName,
                'last_name'      => $lastName,
                'name'           => ucwords($firstName.' '.$lastName),
                'email'          => $uniqueSafeEmail,
                'phone'          => str_replace('.', '', $phoneNumber),
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ];
            $createUser = User::create($userDetails);

            $createUser->roles()->sync(3);

            $buyerDetails = [
                    "user_id"=>1,
                    "buyer_user_id" => $createUser->id,
                    "state" => json_encode([
                        1400,
                        1434
                    ]),
                    "city"=> json_encode([
                        111081,
                        111143
                    ]),
                    "company_name"=> fake('en_US')->company(),
                    "market_preferance"=> "2",
                    "contact_preferance"=> "1",
                    "property_type"=> json_encode([
                        3,
                        7
                    ]),
                    "zoning"=> json_encode([
                        1,
                        2
                    ]),
                    "utilities"=> "1",
                    "sewer"=> "2",
                    "purchase_method"=> json_encode([
                        1
                    ]),
                    "bedroom_min"=> "3",
                    "bedroom_max"=> "4",
                    "bath_min"=> "5",
                    "bath_max"=> "6",
                    "size_min"=> "7",
                    "size_max"=> "8",
                    "lot_size_min"=> "9",
                    "lot_size_max"=> "10",
                    "build_year_min"=> "2017",
                    "build_year_max"=> "2023",
                    "price_min"=> "100000",
                    "price_max"=> "200000",
                    "parking"=> "2",
                    "buyer_type"=> "5",
                    "property_flaw"=> json_encode([
                        1,
                        2
                    ]),
                    "solar"=> "1",
                    "pool"=> "1",
                    "septic"=> "1",
                    "well"=> "1",
                    "hoa"=> "1",
                    "age_restriction"=> "0",
                    "rental_restriction"=> "0",
                    "post_possession"=> "0",
                    "tenant"=> "0",
                    "squatters"=> "0",
                    "building_required"=> "1",
                    "rebuild"=> "1",
                    "foundation_issues"=> "0",
                    "mold"=> "0",
                    "fire_damaged"=> "0"
                ];


                $createUser->buyerVerification()->create(['user_id' => $buyerDetails['buyer_user_id']]);

                $createUser->buyerDetail()->create($buyerDetails);

                if ($createUser->buyerDetail) {
                    //Purchased buyer
                    $syncData['buyer_id'] = $createUser->buyerDetail->id;
                    $syncData['created_at'] = date('Y-m-d H:i:s');

                    User::where('id',1)->first()->purchasedBuyers()->create($syncData);
                }
            
        }
        
    }
}
