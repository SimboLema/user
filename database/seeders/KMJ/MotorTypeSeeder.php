<?php

namespace Database\Seeders\KMJ;

use App\Models\Models\KMJ\MotorType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MotorTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Registered',
                'description' => 'Motor vehicles that are fully registered with licensing authorities',
            ],
            [
                'name' => 'In Transit',
                'description' => 'Motor vehicles that are in transit and not yet fully registered',
            ],
        ];

        foreach ($types as $type) {
            MotorType::create($type);
        }
    }
}
