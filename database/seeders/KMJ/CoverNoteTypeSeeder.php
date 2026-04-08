<?php

namespace Database\Seeders\KMJ;

use App\Models\Models\KMJ\CoverNoteType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoverNoteTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coverNoteTypes = [
            [
                'name' => 'New',
                'description' => 'Sample cover note type for Piah',
            ],
            [
                'name' => 'Renew',
                'description' => 'Standard cover note type',
            ],
            [
                'name' => 'Endorsement',
                'description' => 'Premium cover note type with extra features',
            ],
        ];


        foreach ($coverNoteTypes as $type) {
            CoverNoteType::updateOrCreate(
                ['name' => $type['name']], // avoid duplicates
                ['description' => $type['description']]
            );
        }
    }
}
