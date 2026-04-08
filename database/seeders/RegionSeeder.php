<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Models\KMJ\Region;


class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries=[1, 2, 3];
        $regions = [
            1 => [
                'Tanzania' => [
                    'Arusha',
                    'Dar es Salaam',
                    'Dodoma',
                    'Geita',
                    'Iringa',
                    'Kagera',
                    'Katavi',
                    'Kigoma',
                    'Kilimanjaro',
                    'Lindi',
                    'Manyara',
                    'Mara',
                    'Mbeya',
                    'Morogoro',
                    'Mtwara',
                    'Mwanza',
                    'Njombe',
                    'Pemba North',
                    'Pemba South',
                    'Pwani',
                    'Rukwa',
                    'Ruvuma',
                    'Shinyanga',
                    'Simiyu',
                    'Singida',
                    'Tabora',
                    'Tanga',
                    'Zanzibar North',
                    'Zanzibar South',
                    'Zanzibar West'
                ],

            ],
            2 => [
                'Kenya' => [
                    'Nairobi',
                    'Mombasa',
                    'Kisumu',
                    'Nakuru',
                    'Eldoret',
                    'Nyeri',
                    'Machakos',
                    'Meru',
                    'Thika',
                    'Kitale',
                    'Malindi',
                    'Garissa',
                    'Kakamega',
                    'Bungoma',
                    'Kiambu',
                    'Moyale',
                    'Naivasha',
                    'Lamu',
                    'Mandera',
                    'Narok',
                    'Embu',
                    'Homa Bay',
                    'Kericho',
                    'Migori',
                    'Nyamira',
                    'Kajiado',
                    'Voi',
                    'Kisii',
                    'Kitui',
                    'Machakos',
                    'Kilifi',
                    'Kwale',
                    'Kapsabet',
                    'Kilgoris',
                    'Kakamega',
                    'Bungoma',
                    'Kapenguria',
                    'Isiolo'
                ],

            ],
            3 => [
                'Uganda' => [
                    'Kampala',
                    'Jinja',
                    'Gulu',
                    'Mbale',
                    'Mbarara',
                    'Masaka',
                    'Mukono',
                    'Hoima',
                    'Lira',
                    'Entebbe',
                    'Kasese',
                    'Arua',
                    'Soroti',
                    'Tororo',
                    'Kabale',
                    'Pallisa',
                    'Nakasongola',
                    'Kiboga',
                    'Adjumani',
                    'Kotido',
                    'Kabalega',
                    'Bundibugyo',
                    'Mayuge',
                    'Kamuli',
                    'Ntungamo',
                    'Kalangala',
                    'Mpigi',
                    'Bugiri',
                    'Nebbi',
                    'Kaberamaido',
                    'Luweero',
                    'Amuria',
                    'Mityana',
                    'Kyenjojo',
                    'Moroto',
                    'Nwoya',
                    'Apac',
                    'Katakwi',
                    'Mubende',
                    'Ssembabule',
                    'Kaliro',
                    'Budaka',
                    'Kayunga'
                ]
            ]
        ];

        foreach ($regions as $countryId => $countryRegions) {
            foreach ($countryRegions as $countryName => $regionsArray) {
                sort($regionsArray);
                foreach ($regionsArray as $regionName) {
                    $regionExists = Region::
                        where('name', $regionName)
                        ->where('country_id', $countryId)
                        ->exists();

                    $data = [
                        'country_id' => $countryId,
                        'name' => $regionName,
                    ];

                    if ($regionExists) {
                        Region::where('name', $regionName)
                            ->where('country_id', $countryId)
                            ->update($data);
                    } else {
                        Region::create($data);
                    }
                }
            }
        }
    }
}
