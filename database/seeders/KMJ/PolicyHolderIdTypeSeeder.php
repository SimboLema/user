<?php

namespace Database\Seeders\KMJ;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PolicyHolderIdTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('policy_holder_id_types')->insert([
            [
                'name' => 'National Identification Number (NIN)',
                'description' => 'Government issued national identity card',
            ],
            [
                'name' => 'Voter Registration Number',
                'description' => 'Issued by the electoral commission to register voters',
            ],
            [
                'name' => 'Passport Number',
                'description' => 'Government issued international travel document',
            ],
            [
                'name' => 'Driving License',
                'description' => 'Government issued permit for driving vehicles',
            ],
            [
                'name' => 'Zanzibar Resident ID (ZANID)',
                'description' => 'Issued to residents of Zanzibar for identification',
            ],
            [
                'name' => 'Tax Identification Number (TIN)',
                'description' => 'Issued by the revenue authority for tax purposes',
            ],
            [
                'name' => 'Company Incorporation Certificate Number',
                'description' => 'Unique number issued to a registered company',
            ],
        ]);
    }
}
