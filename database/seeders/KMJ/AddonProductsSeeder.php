<?php

namespace Database\Seeders\KMJ;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddonProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('addon_products')->insert([
            [
                'id' => 1,
                'name' => 'Extension of Georaphical limits beyond East Africa(Tanzania, Kenya, Uganda, Burundi & Rwanda)
5% loading on OD premium',
                'description' => 'Extension of Georaphical limits beyond East Africa(Tanzania, Kenya, Uganda, Burundi & Rwanda)
5% loading on OD premium',
                'amount' => 0.00,
                'amount_type' => 'PREMIUM',
                'rate' => 0.05,
                'applicable_to' => json_encode([
                    'liveCode' => 'SP014003000000',
                    'testCode' => 'SP001003000000',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Loss of use (Max of 21 days @TZS 50,000 per day)
Additional premium of TZS 50,000 per vehicle',
                'description' => 'Loss of use (Max of 21 days @TZS 50,000 per day)
Additional premium of TZS 50,000 per vehicle',
                'amount' => 50000.00,
                'amount_type' => 'NORMAL',
                'rate' => 0.00,
                'applicable_to' => json_encode([
                    'liveCode' => 'SP014001000000',
                    'testCode' => 'SP001001000000',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Excess buy-back 10% loading on the OD premium.',
                'description' => 'Excess buy-back 10% loading on the OD premium.',
                'amount' => 0.00,
                'amount_type' => 'PREMIUM',
                'rate' => 0.10,
                'applicable_to' => json_encode([
                    'liveCode' => 'SP014001000000',
                    'testCode' => 'SP001001000000',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
