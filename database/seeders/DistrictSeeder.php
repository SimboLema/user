<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Models\KMJ\Region;
use App\Models\Models\KMJ\District;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the districts for each region
        $districts = [
            1 => [ // Tanzania
                'Arusha' => [
                    'Arusha City',
                    'Arusha Rural',
                    'Longido',
                    'Meru',
                    'Ngorongoro',
                    'Monduli',
                    'Karatu',
                ],
                'Dar es Salaam' => [
                    'Ilala',
                    'Kinondoni',
                    'Temeke',
                    'Ubungo',
                    'Kigamboni',
                    'Mbagala',
                ],
                'Dodoma' => [
                    'Dodoma Urban',
                    'Dodoma Rural',
                    'Bahi',
                    'Chamwino',
                    'Mpwapwa',
                    'Kondoa',
                ],
                'Geita' => [
                    'Geita Urban',
                    'Geita Rural',
                ],
                'Iringa' => [
                    'Iringa Urban',
                    'Iringa Rural',
                    'Kilolo',
                    'Mufindi',
                ],
                'Kagera' => [
                    'Bukoba Urban',
                    'Bukoba Rural',
                    'Ngara',
                    'Karagwe',
                    'Misenyi',
                    'Biharamulo',
                ],
                'Katavi' => [
                    'Katavi',
                    'Mpanda',
                    'Nsimbo',
                ],
                'Kigoma' => [
                    'Kigoma Urban',
                    'Kigoma Rural',
                    'Buhigwe',
                    'Uvinza',
                    'Kibondo',
                    'Kasulu',
                ],
                'Kilimanjaro' => [
                    'Moshi Urban',
                    'Moshi Rural',
                    'Hai',
                    'Rombo',
                    'Same',
                    'Kilimanjaro',
                ],
                'Lindi' => [
                    'Lindi Urban',
                    'Lindi Rural',
                    'Kilwa',
                    'Ruhua',
                    'Liwale',
                ],
                'Manyara' => [
                    'Babati',
                    'Manyara',
                    'Mbulu',
                    'Hanang',
                    'Simanjiro',
                ],
                'Mara' => [
                    'Musoma Urban',
                    'Musoma Rural',
                    'Serengeti',
                    'Tarime',
                ],
                'Mbeya' => [
                    'Mbeya Urban',
                    'Mbeya Rural',
                    'Rungwe',
                    'Mbarali',
                    'Chunya',
                ],
                'Morogoro' => [
                    'Morogoro Urban',
                    'Morogoro Rural',
                    'Kilombero',
                    'Mvomero',
                ],
                'Mtwara' => [
                    'Mtwara Urban',
                    'Mtwara Rural',
                    'Nanyumbu',
                    'Masasi',
                    'Newala',
                    'Tunduru',
                ],
                'Mwanza' => [
                    'Mwanza Urban',
                    'Mwanza Rural',
                    'Ilemela',
                    'Sengerema',
                    'Misungwi',
                ],
                'Njombe' => [
                    'Njombe Urban',
                    'Njombe Rural',
                    'Makambako',
                    'Wanging’ombe',
                    'Ludewa',
                ],
                'Pemba North' => [
                    'Wete',
                    'Mkoani',
                ],
                'Pemba South' => [
                    'Chake Chake',
                    'Pemba',
                ],
                'Pwani' => [
                    'Pwani',
                    'Bagamoyo',
                    'Kibaha',
                    'Mafia',
                    'Rufiji',
                ],
                'Rukwa' => [
                    'Sumbawanga Urban',
                    'Sumbawanga Rural',
                    'Nkasi',
                    'Kalambo',
                ],
                'Ruvuma' => [
                    'Mbinga',
                    'Songea Urban',
                    'Songea Rural',
                    'Namtumbo',
                    'Tunduru',
                ],
                'Shinyanga' => [
                    'Shinyanga Urban',
                    'Shinyanga Rural',
                    'Kahama',
                    'Busega',
                ],
                'Simiyu' => [
                    'Simiyu',
                    'Bariadi',
                    'Igalula',
                ],
                'Singida' => [
                    'Singida Urban',
                    'Singida Rural',
                    'Manyoni',
                    'Kahama',
                ],
                'Tabora' => [
                    'Tabora Urban',
                    'Tabora Rural',
                    'Urambo',
                    'Ngara',
                ],
                'Tanga' => [
                    'Tanga Urban',
                    'Tanga Rural',
                    'Pangani',
                    'Muheza',
                    'Kilindi',
                ],
                'Zanzibar North' => [
                    'Kaskazini A',
                    'Kaskazini B',
                ],
                'Zanzibar South' => [
                    'Kusini',
                    'Micheweni',
                ],
                'Zanzibar West' => [
                    'Mjini',
                    'Magharibi',
                ],
            ],
            2 => [ // Kenya
                'Nairobi' => [
                    'Nairobi City',
                    'Lang’ata',
                    'Westlands',
                    'Kasarani',
                    'Embakasi',
                ],
                'Mombasa' => [
                    'Mombasa Island',
                    'Nyali',
                    'Kisauni',
                    'Likoni',
                ],
                'Kisumu' => [
                    'Kisumu East',
                    'Kisumu West',
                    'Nyando',
                    'Rangwe',
                ],
                'Nakuru' => [
                    'Nakuru Town East',
                    'Nakuru Town West',
                    'Naivasha',
                    'Gilgil',
                ],
                'Eldoret' => [
                    'Eldoret East',
                    'Eldoret West',
                    'Kapseret',
                    'Soy',
                ],
                'Nyeri' => [
                    'Nyeri Town',
                    'Mathira',
                    'Othaya',
                    'Kieni',
                ],
                'Machakos' => [
                    'Machakos Town',
                    'Masinga',
                    'Kangundo',
                    'Matungulu',
                ],
                'Meru' => [
                    'Meru Town',
                    'Imenti North',
                    'Imenti South',
                    'Buuri',
                ],
                'Thika' => [
                    'Thika Town',
                    'Ruiru',
                    'Gatanga',
                    'Kiambu',
                ],
                'Kitale' => [
                    'Kitale Town',
                    'Kiminini',
                    'Cherangany',
                    'Endebess',
                ],
                'Malindi' => [
                    'Malindi Town',
                    'Ganze',
                    'Kilifi North',
                    'Kilifi South',
                ],
                'Garissa' => [
                    'Garissa Town',
                    'Lagdera',
                    'Fafi',
                    'Dadaab',
                ],
                'Kakamega' => [
                    'Kakamega Town',
                    'Lugari',
                    'Butere',
                    'Malava',
                ],
                'Bungoma' => [
                    'Bungoma Town',
                    'Webuye',
                    'Sirisia',
                    'Chwele',
                ],
                'Kiambu' => [
                    'Kiambu Town',
                    'Ruiru',
                    'Thika',
                    'Gikambura',
                ],
                'Moyale' => [
                    'Moyale Town',
                    'North Horr',
                    'Sololo',
                ],
                'Naivasha' => [
                    'Naivasha',
                    'Gilgil',
                    'Njoro',
                ],
                'Lamu' => [
                    'Lamu Town',
                    'Hulugho',
                    'Witu',
                ],
                'Mandera' => [
                    'Mandera Town',
                    'Banisa',
                    'Lafey',
                    'Takaba',
                ],
                'Narok' => [
                    'Narok North',
                    'Narok South',
                    'Narok East',
                    'Narok West',
                ],
                'Embu' => [
                    'Embu Town',
                    'Mbeere North',
                    'Mbeere South',
                ],
                'Homa Bay' => [
                    'Homa Bay Town',
                    'Mbita',
                    'Ndhiwa',
                    'Suba North',
                    'Suba South',
                ],
                'Kericho' => [
                    'Kericho Town',
                    'Ainamoi',
                    'Belgut',
                    'Kapsoit',
                ],
                'Migori' => [
                    'Migori Town',
                    'Uriri',
                    'Rongo',
                    'Nyatike',
                ],
                'Nyamira' => [
                    'Nyamira Town',
                    'Borabu',
                    'Nyamira',
                ],
                'Kajiado' => [
                    'Kajiado East',
                    'Kajiado North',
                    'Kajiado West',
                ],
                'Voi' => [
                    'Voi Town',
                    'Wundanyi',
                    'Mwatate',
                ],
                'Kisii' => [
                    'Kisii Town',
                    'Masaba South',
                    'Masaba North',
                    'Gucha',
                ],
                'Bomet' => [
                    'Bomet Town',
                    'Chepalungu',
                    'Bomet East',
                    'Bomet Central',
                ],
            ],
            3 => [ // Uganda
                'Kampala' => [
                    'Kampala Central',
                    'Kawempe',
                    'Makindye',
                    'Lubaga',
                    'Nakawa',
                ],
                'Wakiso' => [
                    'Entebbe',
                    'Nansana',
                    'Kira',
                    'Wakiso Town',
                ],
                'Mukono' => [
                    'Mukono Town',
                    'Nangabo',
                    'Goma',
                    'Kanganda',
                ],
                'Jinja' => [
                    'Jinja Municipality',
                    'Jinja North',
                    'Jinja South',
                    'Bugembe',
                ],
                'Mbarara' => [
                    'Mbarara Municipality',
                    'Nyamitanga',
                    'Biharwe',
                    'Rwentondo',
                ],
                'Masaka' => [
                    'Masaka Municipality',
                    'Kalisizo',
                    'Bukomansimbi',
                    'Kalungu',
                ],
                'Soroti' => [
                    'Soroti Municipality',
                    'Kaberamaido',
                    'Serere',
                    'Ngora',
                ],
                'Mbale' => [
                    'Mbale Municipality',
                    'Budadiri',
                    'Namatala',
                    'Nangongera',
                ],
                'Lira' => [
                    'Lira Municipality',
                    'Lira East',
                    'Lira West',
                    'Otuke',
                ],
                'Gulu' => [
                    'Gulu Municipality',
                    'Gulu East',
                    'Gulu West',
                ],
                'Hoima' => [
                    'Hoima Municipality',
                    'Bunyangabu',
                    'Kibaale',
                    'Kiryandongo',
                ],
                'Fort Portal' => [
                    'Fort Portal Municipality',
                    'Kabarole',
                    'Ntoroko',
                ],
                'Kabale' => [
                    'Kabale Municipality',
                    'Rubanda',
                    'Rukiga',
                ],
                'Kasese' => [
                    'Kasese Municipality',
                    'Kasese',
                    'Rwenzori',
                ],
                'Bushenyi' => [
                    'Bushenyi Municipality',
                    'Igara',
                    'Mitooma',
                ],
                'Isingiro' => [
                    'Isingiro Municipality',
                    'Isingiro',
                    'Nyabushozi',
                ],
                'Rukungiri' => [
                    'Rukungiri Municipality',
                    'Rukungiri',
                    'Kibale',
                ],
                'Kiboga' => [
                    'Kiboga Municipality',
                    'Kiboga',
                    'Mubende',
                ],
                'Sembabule' => [
                    'Sembabule Municipality',
                    'Sembabule',
                    'Lwengo',
                ],
                'Kiryandongo' => [
                    'Kiryandongo',
                    'Buliisa',
                    'Masindi',
                ],
            ]
        ];

        // Loop through each region and district to create records
        foreach ($districts as $regionId => $districtList) {
            foreach ($districtList as $regionName => $districts) {
                $region = Region::where('name',$regionName)->first();

                if ($region) {
                    foreach ($districts as $districtName) {
                        District::updateOrCreate([
                            'name' => $districtName,
                            'region_id' => $region->id,
                        ],[
                            'name' => $districtName,
                            'region_id' => $region->id,
                        ]);
                    }
                }
            }

        }
    }
}
