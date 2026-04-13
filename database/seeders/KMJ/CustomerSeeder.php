<?php

namespace Database\Seeders\KMJ;

use App\Models\Models\KMJ\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Baraka Juma',
                'dob' => '1950-02-05',
                'policy_holder_type_id' => 1,
                'policy_holder_id_number' => 'T143041786',
                'policy_holder_id_type_id' => 3, // valid for test
                'gender' => 'M',
                'district_id' => 1, // lazima u-match na districts table yako
                'street' => 'Sinza, Ngamia Street',
                'phone' => '255680522062',
                'fax' => null,
                'postal_address' => 'P.O.BOX 1233, Dar es Salaam',
                'email_address' => '',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create(
                $customer
            );
        }
    }
}
