<?php

namespace Database\Seeders\KMJ;

use App\Models\Models\KMJ\TaxExemptionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxExemptionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        $types = [
            ['name' => 'PolicyHolder exempted'],
            ['name' => 'Risk exempted'],
        ];

        TaxExemptionType::insert($types);
    }
}
