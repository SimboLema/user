<?php

namespace Database\Seeders\KMJ;

use App\Models\Models\KMJ\ReinsuranceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReinsuranceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReinsuranceType::create([
            'name' => 'Facultative Proportion-Quota Share',
            'description' => 'Facultative reinsurance, Proportion-Quota share',
        ]);

        ReinsuranceType::create([
            'name' => 'Facultative Non Proportion-Excess of Loss',
            'description' => 'Facultative reinsurance, Non-Proportion, Excess of Loss',
        ]);

        ReinsuranceType::create([
            'name' => 'Facultative Proportion-Surplus Treaty',
            'description' => 'Facultative reinsurance, Proportion-Surplus Treaty',
        ]);

        ReinsuranceType::create([
            'name' => 'Facultative Facultative Obligatory',
            'description' => 'Facultative reinsurance, Obligatory',
        ]);

        ReinsuranceType::create([
            'name' => 'Treaty Proportion-Quota Share',
            'description' => 'Treaty reinsurance, Proportion-Quota share',
        ]);

        ReinsuranceType::create([
            'name' => 'Treaty Proportion-Surplus Treaty',
            'description' => 'Treaty reinsurance, Proportion-Surplus Treaty',
        ]);

        ReinsuranceType::create([
            'name' => 'Treaty Non Proportion-Excess of Loss',
            'description' => 'Treaty reinsurance, Non-Proportion, Excess of Loss',
        ]);

        ReinsuranceType::create([
            'name' => 'Treaty Non Proportion-Stop Loss',
            'description' => 'Treaty reinsurance, Non-Proportion, Stop Loss',
        ]);
    }
}
