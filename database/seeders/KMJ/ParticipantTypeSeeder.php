<?php

namespace Database\Seeders\KMJ;

use App\Models\Models\KMJ\ParticipantType;
use App\Models\Models\KMJ\ReinsuranceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParticipantTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ParticipantType::create([
            'name' => 'Leader',
            'description' => 'Type of participation: Leader',
        ]);

        ParticipantType::create([
            'name' => 'Treaty Cession Outward',
            'description' => 'Type of participation: Treaty Cession outward',
        ]);

        ParticipantType::create([
            'name' => 'Policy Cession Outward',
            'description' => 'Type of participation: Policy Cession outward',
        ]);

        ParticipantType::create([
            'name' => 'Facultative Outward Local',
            'description' => 'Type of participation: Facultative Outward Local',
        ]);

        ParticipantType::create([
            'name' => 'Facultative Outward Abroad',
            'description' => 'Type of participation: Facultative Outward Abroad',
        ]);

        ParticipantType::create([
            'name' => 'Facultative Inward Local',
            'description' => 'Type of participation: Facultative Inward Local',
        ]);

        ParticipantType::create([
            'name' => 'Facultative Inward Abroad',
            'description' => 'Type of participation: Facultative Inward Abroad',
        ]);
    }
}
