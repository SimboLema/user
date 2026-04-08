<?php

namespace Database\Seeders\KMJ;

use App\Models\Models\KMJ\OwnerCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OwnerCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Sole Proprietor',
                'description' => 'Individual owner of the motor vehicle',
            ],
            [
                'name' => 'Corporate',
                'description' => 'Company or organization owning the motor vehicle',
            ],
        ];

        foreach ($categories as $category) {
            OwnerCategory::create($category);
        }
    }
}
