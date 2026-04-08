<?php

namespace Database\Seeders\KMJ;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentModeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
       public function run(): void
    {
        DB::table('payment_modes')->insert([
            [
                'name' => 'Cash',
                'description' => 'Payment made using physical cash',
            ],
            [
                'name' => 'Cheque',
                'description' => 'Payment made via cheque',
            ],
            [
                'name' => 'EFT (Mobile)',
                'description' => 'Electronic Funds Transfer via bank',
            ],
        ]);
    }
}
