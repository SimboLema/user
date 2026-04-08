<?php

namespace Database\Seeders\KMJ;

use App\Models\Models\KMJ\MotorUsage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MotorUsageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usages = [
            [
                'name' => 'Private',
                'description' => 'Motor vehicles used for personal and non-commercial purposes',
            ],
            [
                'name' => 'Commercial',
                'description' => 'Motor vehicles used for business, trade, or professional services',
            ],
        ];

        foreach ($usages as $usage) {
            MotorUsage::create($usage);
        }
    }
}
