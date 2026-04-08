<?php

namespace Database\Seeders\KMJ;

use App\Models\Models\KMJ\ReinsuranceForm;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReinsuranceFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReinsuranceForm::create([
            'name' => 'Policy Cession',
            'description' => 'Form of reinsurance: Policy Cession',
        ]);

        ReinsuranceForm::create([
            'name' => 'Treaty Cession',
            'description' => 'Form of reinsurance: Treaty Cession',
        ]);

        ReinsuranceForm::create([
            'name' => 'Facultative',
            'description' => 'Form of reinsurance: Facultative',
        ]);
    }
}
