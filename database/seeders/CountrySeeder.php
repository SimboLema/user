<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Models\KMJ\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $countries = [
            ['name' => 'Tanzania', 'code' => 'TZA'],
            ['name' => 'Kenya', 'code' => 'KEN'],
            ['name' => 'Uganda', 'code' => 'UGA'],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(
                ['code' => $country['code']],
                ['name' => $country['name']]
            );
        }
    }

}
