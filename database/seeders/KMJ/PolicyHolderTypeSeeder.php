<?php

namespace Database\Seeders\KMJ;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PolicyHolderTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        DB::table('policy_holder_types')->insert([
            [
                'name' => 'Individual',
                'description' => 'A single person purchasing a policy',
            ],
            [
                'name' => 'Cooperate',
                'description' => 'An organization or business entity holding a policy',
            ],
        ]);
    }
}
