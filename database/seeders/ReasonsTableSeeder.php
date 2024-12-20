<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReasonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('reasons')->insert([
            ['name' => 'Spam', 'description' => 'Unwanted or irrelevant messages.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Inappropriate Language', 'description' => 'Use of offensive, abusive, or profane language.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Harassment', 'description' => 'Targeted harassment, bullying, or threatening behavior.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Misinformation', 'description' => 'Sharing false or misleading information.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fraud or Scam', 'description' => 'Attempts to deceive or defraud others.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hate Speech', 'description' => 'Promoting hate or violence against individuals or groups.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Privacy Violation', 'description' => 'Sharing private or sensitive information without consent.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Copyright Violation', 'description' => 'Use of copyrighted material without permission.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Impersonation', 'description' => 'Pretending to be someone else to deceive others.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Explicit Content', 'description' => 'Sharing sexually explicit or graphic content.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Illegal Activity', 'description' => 'Promoting or engaging in illegal activities.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Self-Harm or Suicide Threats', 'description' => 'Posts or messages indicating harm to oneself or others.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Offensive Content', 'description' => 'Content that is offensive or disturbing to the community.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Unwanted Solicitation', 'description' => 'Unapproved advertisements or solicitations.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Other', 'description' => 'Any other reason not covered above.', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
