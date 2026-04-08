<?php

namespace Database\Seeders\KMJ;

use App\Models\Models\KMJ\MotorCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MotorCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Motor Vehicle',
                'description' => 'Four-wheeled vehicles such as cars, buses, and trucks',
            ],
            [
                'name' => 'Motor Cycle',
                'description' => 'Two-wheeled motorized vehicles including scooters and motorcycles',
            ],
        ];

        foreach ($categories as $category) {
            MotorCategory::create($category);
        }
    }
}
