<?php

namespace Database\Seeders\KMJ;

use App\Models\Models\KMJ\Insurance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InsuranceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // -------------------------
        // Step 1: Insert Insurances
        // -------------------------
        $insurances = [
            ['name' => 'GOODS IN TRANSIT'],
            ['name' => 'MOTOR'],
            ['name' => 'MISCELLANEOS AND ACCIDENTS'],
            ['name' => 'ENGINEERING'],
            ['name' => 'FIRE'],
            ['name' => 'MARINE'],
        ];

        foreach ($insurances as $insuranceData) {
            $insurance = Insurance::create($insuranceData);

            // -------------------------
            // Step 2: Add Products for this Insurance
            // -------------------------
            if ($insurance->name == 'GOODS IN TRANSIT') {
                $products = [
                    [
                        'code' => 'SP002001000000',
                        'name' => 'Own Transport within Tanzania- All Risks Containerised',
                    ],
                    [
                        'code' => 'SP002002000000',
                        'name' => 'Own Transport within Tanzania- All Risks Non-Containerised',
                    ],
                    [
                        'code' => 'SP002003000000',
                        'name' => 'Hauliers Liability within Tanzania- All Risks Containerised',
                    ],
                    [
                        'code' => 'SP002004000000',
                        'name' => 'Hauliers Liability within Tanzania- All Risks Non-Containerised',
                    ],
                    [
                        'code' => 'SP002005000000',
                        'name' => 'Standard GIT(Fire,Collision and Overturning cover)Own Transport',
                    ],
                    [
                        'code' => 'SP002006000000',
                        'name' => 'Standard GIT(Fire,Collision and Overturning cover)Own Transport - Non-Containerised',
                    ],
                ];

                foreach ($products as $productData) {
                    $product = $insurance->products()->create($productData);

                    // -------------------------
                    // Step 3: Add Coverages for this Product
                    // -------------------------
                    if ($product->code == 'SP002001000000') {
                        $coverages = [
                            [
                                'risk_name' => 'Own Transport within Tanzania- All Risks Containerised- Produce Raw Agricultural products including sisal cotton coffee tea cocoa rice, etc',
                                'risk_code' => 'SP002001000001',
                                'rate' => 0.35,
                                'minimum_amount' => 0,
                            ],
                            [
                                'risk_name' => 'Own Transport within Tanzania- All Risks Containerised- Non fragile General merchandise/manufactured goods e.g. Textiles, iron bars etc',
                                'risk_code' => 'SP002001000002',
                                'rate' => 0.4,
                                'minimum_amount' => 0,
                            ],
                            [
                                'risk_name' => 'Own Transport within Tanzania- All Risks Containerised- Semi-fragile general merchandise/manufactured goods e.g. electrical appliances like refrigerators, radios etc and domestic appliances like Thermos flasks, washing machine….',
                                'risk_code' => 'SP002001000003',
                                'rate' => 0.5,
                                'minimum_amount' => 0,
                            ],
                            [
                                'risk_name' => 'Own Transport within Tanzania- All Risks Containerised- Fragile General Merchandise/ Manufactured goods e.g. glass, glassware, glass louvers/sheets chinaware/sheets chinaware',
                                'risk_code' => 'SP010001000004',
                                'rate' => 0.6,
                                'minimum_amount' => 0,
                            ],
                            [
                                'risk_name' => 'Own Transport within Tanzania- All Risks Containerised- Petroleum products in tankers etc (Liquid cargo)',
                                'risk_code' => 'SP002001000005',
                                'rate' => 1.3,
                                'minimum_amount' => 0,
                            ],
                            [
                                'risk_name' => 'Own Transport within Tanzania- All Risks Containerised- Liquid Cargo (other liquid)',
                                'risk_code' => 'SP002001000006',
                                'rate' => 0.85,
                                'minimum_amount' => 0,
                            ],
                        ];

                        foreach ($coverages as $coverageData) {
                            $product->coverages()->create($coverageData);
                        }
                    }
                }
            }
            if ($insurance->name == 'MOTOR') {
                $products = [
                    [
                        'code' => 'SP001001000000',
                        'name' => 'MOTOR PRIVATE VEHICLE',
                    ],
                    [
                        'code' => 'SP001002000000',
                        'name' => 'MOTOR MOTOR CYCLE',
                    ],
                    [
                        'code' => 'SP001003000000',
                        'name' => 'MOTOR COMMERCIAL VEHICLE',
                    ],
                    [
                        'code' => 'SP001004000000',
                        'name' => 'MOTOR PASSENGER CARRYING',
                    ],
                    [
                        'code' => 'SP001005000000',
                        'name' => 'MOTOR SPECIAL TYPE VEHICLES',
                    ],
                ];

                foreach ($products as $productData) {
                    $product = $insurance->products()->create($productData);

                    switch ($product->code) {
                        case 'SP001001000000': // MOTOR PRIVATE VEHICLE
                            $coverages = [
                                [
                                    'risk_code' => 'SP001001000001',
                                    'risk_name' => 'MOTOR Private Vehicles-Comprehensive claim free Vehicle',
                                    'rate' => 3.5,
                                    'minimum_amount' => 250000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001001000002',
                                    'risk_name' => 'MOTOR Private Vehicles-Comprehensive with claim record',
                                    'rate' => 4,
                                    'minimum_amount' => 250000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001001000003',
                                    'risk_name' => 'MOTOR Private Vehicles-TPFT',
                                    'rate' => 2,
                                    'minimum_amount' => 200000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001001000004',
                                    'risk_name' => 'MOTOR Private Vehicles-TPO',
                                    'rate' => 0,
                                    'minimum_amount' => 100000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'Yes',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                            ];
                            break;

                        case 'SP001002000000': // MOTOR MOTOR CYCLE
                            $coverages = [
                                [
                                    'risk_code' => 'SP001002000001',
                                    'risk_name' => 'MOTOR Motor Cycle-Comprehensive-Two wheelers claims free',
                                    'rate' => 5,
                                    'minimum_amount' => 0,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001002000002',
                                    'risk_name' => 'MOTOR Motor Cycle-Comprehensive-Two wheelers with claims record',
                                    'rate' => 6,
                                    'minimum_amount' => 0,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001002000003',
                                    'risk_name' => 'MOTOR Motor Cycle-Comprehensive-Three Wheelers claims free',
                                    'rate' => 6,
                                    'minimum_amount' => 125000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001002000004',
                                    'risk_name' => 'MOTOR Motor Cycle-Comprehensive-Three wheelers with claims record',
                                    'rate' => 7,
                                    'minimum_amount' => 125000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ])
                                ],
                                [
                                    'risk_code' => 'SP001002000005',
                                    'risk_name' => 'MOTOR Motor Cycle-TPFT-Two wheelers',
                                    'rate' => 3.5,
                                    'minimum_amount' => 100000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 50000,
                                    ])
                                ],
                                [
                                    'risk_code' => 'SP001002000006',
                                    'risk_name' => 'MOTOR Motor Cycle-TPFT-Three wheelers',
                                    'rate' => 3.5,
                                    'minimum_amount' => 100000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 75000,
                                    ])
                                ],
                                [
                                    'risk_code' => 'SP001002000007',
                                    'risk_name' => 'MOTOR Motor Cycle-TPO',
                                    'rate' => 0,
                                    'minimum_amount' => 50000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001002000008',
                                    'risk_name' => 'MOTOR Motor Cycle-TPO (Three Wheelers)',
                                    'rate' => 0,
                                    'minimum_amount' => 75000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                            ];
                            break;

                        case 'SP001003000000': // MOTOR COMMERCIAL VEHICLE
                            $coverages = [
                                [
                                    'risk_code' => 'SP001003000001',
                                    'risk_name' => 'MOTOR Commercial Vehicle-General Goods Carrying Own Goods (Comprehensive) claim free',
                                    'rate' => 4.25,
                                    'minimum_amount' => 500000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001003000002',
                                    'risk_name' => 'MOTOR Commercial Vehicle-General Goods Carrying Own Goods (Comprehensive) with claim record',
                                    'rate' => 4.75,
                                    'minimum_amount' => 500000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001003000003',
                                    'risk_name' => 'MOTOR Commercial Vehicle-Own Goods-TPFT-Up to 2 tonnes',
                                    'rate' => 2.5,
                                    'minimum_amount' => 350000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 150000,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001003000004',
                                    'risk_name' => 'MOTOR Commercial Vehicle-Own Goods-TPFT-Above 2 to 5 tonnes',
                                    'rate' => 2.5,
                                    'minimum_amount' => 350000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 200000,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001003000005',
                                    'risk_name' => 'MOTOR Commercial Vehicle-Own Goods-TPFT-In excess of 5 tonnes but less than 10 tonnes',
                                    'rate' => 2.5,
                                    'minimum_amount' => 350000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 250000,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001003000006',
                                    'risk_name' => 'MOTOR Commercial Vehicle-Own Goods-TPFT-In excess of 10 tonnes',
                                    'rate' => 2.5,
                                    'minimum_amount' => 350000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 300000,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001003000007',
                                    'risk_name' => 'MOTOR Commercial Vehicle-General Cartage claims free',
                                    'rate' => 5,
                                    'minimum_amount' => 350000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001003000008',
                                    'risk_name' => 'MOTOR Commercial Vehicle-General Cartage with claims record',
                                    'rate' => 5.75,
                                    'minimum_amount' => 350000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001003000009',
                                    'risk_name' => 'MOTOR Commercial Vehicle-General Cartage-TPFT-Up to 2 tonnes',
                                    'rate' => 3,
                                    'minimum_amount' => 350000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 150000,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001003000010',
                                    'risk_name' => 'MOTOR Commercial Vehicle-General Cartage-TPFT-Above 2 to 5 tonnes',
                                    'rate' => 3,
                                    'minimum_amount' => 350000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 200000,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001003000011',
                                    'risk_name' => 'MOTOR Commercial Vehicle-General Cartage-TPFT-In excess of 5 tonnes but less than 10 tonnes',
                                    'rate' => 3,
                                    'minimum_amount' => 350000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 250000,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001003000012',
                                    'risk_name' => 'MOTOR Commercial Vehicle-General Cartage-TPFT-In excess of 10 tonnes',
                                    'rate' => 3,
                                    'minimum_amount' => 350000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 300000,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001003000013',
                                    'risk_name' => 'MOTOR Commercial Vehicle-General Goods Carrying-TPO Up to 2 tonnes',
                                    'rate' => 0,
                                    'minimum_amount' => 150000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001003000014',
                                    'risk_name' => 'MOTOR Commercial Vehicle-General Goods Carrying-TPO Above 2 to 5 tonnes',
                                    'rate' => 0,
                                    'minimum_amount' => 200000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001003000015',
                                    'risk_name' => 'MOTOR Commercial Vehicle-General Goods Carrying-TPO In excess of 5 tonnes but less than 10 tonnes',
                                    'rate' => 0,
                                    'minimum_amount' => 250000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001003000016',
                                    'risk_name' => 'MOTOR Commercial Vehicle-General Goods Carrying-TPO In excess of 10 tonnes',
                                    'rate' => 0,
                                    'minimum_amount' => 300000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001003000017',
                                    'risk_name' => 'MOTOR Commercial Vehicle-Trailers - Comprehensive:Trailers manufactured locally/bought from the dealer and less than 10 years old -Claims Free',
                                    'rate' => 4.0,
                                    'minimum_amount' => 0,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001003000018',
                                    'risk_name' => 'MOTOR Commercial Vehicle-Trailers - Comprehensive:Trailers manufactured locally/bought from the dealer and less than 10 years old -with Claim record',
                                    'rate' => 4.75,
                                    'minimum_amount' => 0,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001003000019',
                                    'risk_name' => 'MOTOR Commercial Vehicle-Trailers - Comprehensive:Conversion trailers or trailers over 10 years old - Claims Free',
                                    'rate' => 5.25,
                                    'minimum_amount' => 0,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001003000020',
                                    'risk_name' => 'MOTOR Commercial Vehicle-Trailers - Comprehensive:Conversion trailers or trailers over 10 years old- with Claim record',
                                    'rate' => 5.75,
                                    'minimum_amount' => 0,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001003000021',
                                    'risk_name' => 'MOTOR Commercial Vehicle-Trailers - Third Party Only',
                                    'rate' => 0,
                                    'minimum_amount' => 100000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001003000022',
                                    'risk_name' => 'MOTOR Commercial Vehicle-Oil tankers- Comprehensive:Steel Tankers below 10 years',
                                    'rate' => 6.0,
                                    'minimum_amount' => 2000000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001003000023',
                                    'risk_name' => 'MOTOR Commercial Vehicle-Oil tankers- Comprehensive:Aluminium Tankers below 10 years',
                                    'rate' => 7.0,
                                    'minimum_amount' => 2000000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001003000024',
                                    'risk_name' => 'MOTOR Commercial Vehicle-Oil tankers- Comprehensive:Tankers over 10 years old',
                                    'rate' => 8.0,
                                    'minimum_amount' => 2000000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001003000025',
                                    'risk_name' => 'MOTOR Commercial Vehicle-Oil tankers- Comprehensive:TPFT',
                                    'rate' => 4.0,
                                    'minimum_amount' => 1500000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 750000,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001003000026',
                                    'risk_name' => 'MOTOR Commercial Vehicle-Oil tankers- Comprehensive:TPO',
                                    'rate' => 0,
                                    'minimum_amount' => 750000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                            ];
                            break;

                        case 'SP001004000000': // MOTOR PASSENGER CARRYING
                            $coverages = [
                                [
                                    'risk_code' => 'SP001004000001',
                                    'risk_name' => 'MOTOR Passenger Carrying-Comprehensive-Public Taxis, private hire, tour operators with No Claim Record',
                                    'rate' => 5.5,
                                    'minimum_amount' => 500000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'Yes',
                                        'tppamount' => 0,
                                        'persetamount' => 15000,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001004000002',
                                    'risk_name' => 'MOTOR Passenger Carrying-Comprehensive Public Taxis, private hire, tour operators with claim record',
                                    'rate' => 6,
                                    'minimum_amount' => 500000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001004000003',
                                    'risk_name' => 'MOTOR Passenger Carrying-Comprehensive-Buses-Daladala within City',
                                    'rate' => 8,
                                    'minimum_amount' => 0,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'Yes',
                                        'tppamount' => 0,
                                        'persetamount' => 15000,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001004000004',
                                    'risk_name' => 'MOTOR Passenger Carrying-Comprehensive-Buses-Up Country',
                                    'rate' => 8,
                                    'minimum_amount' => 0,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'Yes',
                                        'tppamount' => 0,
                                        'persetamount' => 30000,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001004000005',
                                    'risk_name' => 'MOTOR Passenger Carrying-Comprehensive-Buses-Private',
                                    'rate' => 5,
                                    'minimum_amount' => 0,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'Yes',
                                        'tppamount' => 0,
                                        'persetamount' => 10000,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001004000006',
                                    'risk_name' => 'MOTOR Passenger Carrying-Comprehensive-Buses-School',
                                    'rate' => 5,
                                    'minimum_amount' => 0,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'Yes',
                                        'tppamount' => 0,
                                        'persetamount' => 7500,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001004000007',
                                    'risk_name' => 'MOTOR Passenger Carrying-Third Party Premium Public Taxis, private hire, tour operators',
                                    'rate' => 0,
                                    'minimum_amount' => 150000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'Yes',
                                        'tppamount' => 0,
                                        'persetamount' => 15000,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001004000008',
                                    'risk_name' => 'MOTOR Passenger Carrying-Third Party Premium Buses-Daladala within City',
                                    'rate' => 0,
                                    'minimum_amount' => 150000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'Yes',
                                        'tppamount' => 0,
                                        'persetamount' => 15000,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001004000009',
                                    'risk_name' => 'MOTOR Passenger Carrying-Third Party Premium Buses- Up Country',
                                    'rate' => 0,
                                    'minimum_amount' => 150000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'Yes',
                                        'tppamount' => 0,
                                        'persetamount' => 30000,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001004000010',
                                    'risk_name' => 'MOTOR Passenger Carrying-Third Party Premium Buses- Private',
                                    'rate' => 0,
                                    'minimum_amount' => 150000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'Yes',
                                        'tppamount' => 0,
                                        'persetamount' => 10000,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001004000011',
                                    'risk_name' => 'MOTOR Passenger Carrying-Third Party Premium Buses- School',
                                    'rate' => 0,
                                    'minimum_amount' => 150000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'Yes',
                                        'tppamount' => 0,
                                        'persetamount' => 7500,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                            ];
                            break;

                        case 'SP001005000000': // MOTOR SPECIAL TYPE VEHICLES
                            $coverages = [
                                [
                                    'risk_code' => 'SP001005000001',
                                    'risk_name' => 'MOTOR Special Type Vehicles-Comprehensive-Farm Tractors, Forklifts, Graders, Cranes, Excavators etc.',
                                    'rate' => 2,
                                    'minimum_amount' => 250000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                                [
                                    'risk_code' => 'SP001005000002',
                                    'risk_name' => 'MOTOR Special Type Vehicles-Third Party Premium-Farm Tractors, Forklifts, Graders, Cranes, Excavators etc.',
                                    'rate' => 0,
                                    'minimum_amount' => 100000,
                                    'parameters' => json_encode([
                                        'plustpp' => 'No',
                                        'isperset' => 'No',
                                        'tppamount' => 0,
                                        'persetamount' => 0,
                                        'additionalamount' => 0,
                                    ]),
                                ],
                            ];
                            break;
                    }

                    // Save coverages
                    foreach ($coverages as $coverageData) {
                        $product->coverages()->create($coverageData);
                    }
                }
            }
        }
    }
}
