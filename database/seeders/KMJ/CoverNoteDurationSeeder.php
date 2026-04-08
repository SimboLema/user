<?php

namespace Database\Seeders\KMJ;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoverNoteDurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cover_note_durations')->insert([
            ['months' => 1,  'label' => '1 Month'],
            ['months' => 3,  'label' => '3 Months'],
            ['months' => 6,  'label' => '6 Months'],
            ['months' => 9,  'label' => '9 Months'],
            ['months' => 12, 'label' => '1 Year'],
        ]);
    }
}
