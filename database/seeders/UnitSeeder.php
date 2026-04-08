<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create Kilogram first (Base unit for mass)
        $kilogram = Unit::updateOrCreate(
            ['id'=>1],
            [
                'name' => 'Kilogram',
                'symbol' => 'kg',
                'type' => 'mass',
                'base_unit_id' => null, // As requested
                'operation_type' => null, // No operation needed for the base unit
                'operation_value' => null, // No operation needed for the base unit
                'created_by' => 1, // Set the creator (change as needed)
                'archive' => 0, // Not archived
            ]
        );

        // Create Tonne using Kilogram as base unit
        Unit::updateOrCreate(
            ['id'=>2],
            [
                'name' => 'Tonne',
                'symbol' => 't',
                'type' => 'mass',
                'base_unit_id' => $kilogram->id, // Link to Kilogram as the base unit
                'operation_type' => 'multiply', // Tonne is a multiple of Kilogram
                'operation_value' => 1000, // 1 Tonne = 1000 Kilograms
                'created_by' => 1, // Set the creator (change as needed)
                'archive' => 0, // Not archived
            ]
        );
    }
}
