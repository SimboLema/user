<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Seeders\KMJ\AddonProductsSeeder;
use Database\Seeders\KMJ\CountryRegionDistrictSeeder;
use Database\Seeders\KMJ\CoverNoteDurationSeeder;
use Database\Seeders\KMJ\CoverNoteTypeSeeder;
use Database\Seeders\KMJ\CurrencySeeder;
use Database\Seeders\KMJ\CustomerSeeder;
use Database\Seeders\KMJ\EndorsementTypeSeeder;
use Database\Seeders\KMJ\InsuranceSeeder;
use Database\Seeders\KMJ\MotorCategorySeeder;
use Database\Seeders\KMJ\MotorTypeSeeder;
use Database\Seeders\KMJ\MotorUsageSeeder;
use Database\Seeders\KMJ\OwnerCategorySeeder;
use Database\Seeders\KMJ\ParticipantTypeSeeder;
use Database\Seeders\KMJ\PaymentModeSeeder;
use Database\Seeders\KMJ\PolicyHolderIdTypeSeeder;
use Database\Seeders\KMJ\PolicyHolderTypeSeeder;
use Database\Seeders\KMJ\ReinsuranceCategorySeeder;
use Database\Seeders\KMJ\ReinsuranceFormSeeder;
use Database\Seeders\KMJ\ReinsuranceTypeSeeder;
use Database\Seeders\KMJ\TaxExemptionTypeSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            CountryRegionDistrictSeeder::class,
            CoverNoteDurationSeeder::class,
            CurrencySeeder::class,
            InsuranceSeeder::class,
            PaymentModeSeeder::class,
            PolicyHolderIdTypeSeeder::class,
            PolicyHolderTypeSeeder::class,
            CoverNoteTypeSeeder::class,
            CustomerSeeder::class,
            ReinsuranceCategorySeeder::class,
            ParticipantTypeSeeder::class,
            ReinsuranceFormSeeder::class,
            ReinsuranceTypeSeeder::class,
            MotorCategorySeeder::class,
            MotorTypeSeeder::class,
            MotorUsageSeeder::class,
            OwnerCategorySeeder::class,
            EndorsementTypeSeeder::class,
            TaxExemptionTypeSeeder::class,
            AddonProductsSeeder::class,
        ]);
    }
}
