<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class FackerSellerSeeder extends Seeder
{
    public function run()
    {
        
        // Seller
        foreach (range(1, 50) as $index){
            $firstName = fake('en_US')->firstName();
            $lastName = fake('en_US')->lastName();

            $uniqueSafeEmail = fake('en_US')->unique()->safeEmail; 
          
            $userRecords =  [
                'first_name'     => $firstName,
                'last_name'      => $lastName,
                'name'           => $firstName.' '.$lastName,
                'email'          => $uniqueSafeEmail,
                'password'       => bcrypt('buyboxbot@1234'),
                'remember_token' => null,
                'level_type'     => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ];

            $userCreated = User::create($userRecords);

            $userCreated->roles()->sync(2);
            
        }
        
    }
}
