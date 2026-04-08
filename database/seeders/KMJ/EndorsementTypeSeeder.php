<?php

namespace Database\Seeders\KMJ;

use App\Models\Models\KMJ\EndorsementType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EndorsementTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Increasing Premium Charged'],
            ['name' => 'Decreasing Premium Charged'],
            ['name' => 'Cover Details Change'],
            ['name' => 'Cancellation'],
        ];

        EndorsementType::insert($types);
    }
}
