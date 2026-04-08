<?php

namespace Database\Seeders\KMJ;

use App\Models\Models\KMJ\ReinsuranceCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReinsuranceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReinsuranceCategory::create([
            'name' => 'Facultative Outward',
            'description' => 'For facultative outward reinsurance',
        ]);

        ReinsuranceCategory::create([
            'name' => 'Facultative Inward',
            'description' => 'For facultative inward reinsurance',
        ]);
    }
}
