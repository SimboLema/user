<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\District;
use App\Models\Ward;

class WardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define wards under districts
        $wards = [
            'Arusha City' => [
                'Sekei', 'Ngarenaro', 'Kaloleni', 'Unga Limited', 'Kimandolu', 
                'Elerai', 'Themi', 'Levolosi', 'Olorieni'
            ],
            'Arusha Rural' => [
                'Oljoro', 'Nduruma', 'Olkokola', 'Mlangarini', 
                'Mateves', 'Kimnyaki'
            ],
            'Longido' => [
                'Longido', 'Engikaret', 'Orbomba', 'Kitumbeine', 
                'Elang’ata Dapash', 'Namanga'
            ],
            'Meru' => [
                'Poli', 'Maji ya Chai', 'Usa River', 'Akheri', 
                'King’ori', 'Kikatiti'
            ],
            'Ngorongoro' => [
                'Loliondo', 'Sale', 'Pinyinyi', 'Enduleni', 
                'Ngorongoro', 'Digodigo'
            ],
            'Monduli' => [
                'Monduli Juu', 'Engutoto', 'Lolkisale', 'Mto wa Mbu', 
                'Makuyuni', 'Selela'
            ],
            'Karatu' => [
                'Karatu', 'Qurus', 'Endabash', 'Mangola', 
                'Rhotia'
            ],
            'Ilala' => [
                'Kariakoo', 'Upanga', 'Gerezani', 'Mchikichini', 
                'Jangwani', 'Buguruni', 'Kivukoni'
            ],
            'Kinondoni' => [
                'Magomeni', 'Mwananyamala', 'Makumbusho', 'Msasani', 
                'Sinza', 'Kawe'
            ],
            'Temeke' => [
                'Kurasini', 'Mbagala', 'Kigamboni', 'Temeke', 
                'Chang’ombe'
            ],
            'Ubungo' => [
                'Makuburi', 'Sinza', 'Mabibo', 'Manzese', 
                'Mbezi Louis'
            ],
            'Kigamboni' => [
                'Kibada', 'Kijichi', 'Kimbiji', 'Kigamboni', 
                'Mji Mwema'
            ],
            'Mbagala' => [
                'Charambe', 'Yombo Vituka', 'Kijichi', 'Chamazi', 
                'Mbagala'
            ],
            'Dodoma Urban' => [
                'Kikuyu', 'Chamwino', 'Nkuhungu', 'Makole', 
                'Viwandani'
            ],
            'Dodoma Rural' => [
                'Zuzu', 'Mpunguzi', 'Mtumba', 'Makutupora', 
                'Msamalo'
            ],
            'Bahi' => [
                'Chipanga', 'Mpamantwa', 'Mundemu', 'Bahi', 
                'Chikola'
            ],
            'Chamwino' => [
                'Itiso', 'Haneti', 'Makangwa', 'Chamwino', 
                'Manchali'
            ],
            'Mpwapwa' => [
                'Mazae', 'Kibakwe', 'Chunyu', 'Mpwapwa', 
                'Mima'
            ],
            'Kondoa' => [
                'Pahi', 'Kondoa Mjini', 'Kolo', 'Masange', 
                'Salanka'
            ],
            'Geita Urban' => [
                'Kalangalala', 'Nyankumbu', 'Buhalahala', 'Mtakuja'
            ],
            'Geita Rural' => [
                'Katoro', 'Nyang’hwale', 'Chato', 'Bukoli'
            ],
            'Iringa Urban' => [
                'Kihesa', 'Gangilonga', 'Ruaha', 'Kitwiru', 
                'Mkwawa'
            ],
            'Iringa Rural' => [
                'Mlolo', 'Magulilwa', 'Kalenga', 'Idodi'
            ],
            'Kilolo' => [
                'Ilula', 'Ruaha Mbuyuni', 'Idete', 'Kilolo'
            ],
            'Mufindi' => [
                'Mafinga', 'Igowole', 'Mdabulo', 'Ifwagi'
            ],
            'Bukoba Urban' => [
                'Nyanga', 'Kashai', 'Nshambya', 'Miembeni', 'Kitendaguro'
            ],
            'Bukoba Rural' => [
                'Bugabo', 'Kishanje', 'Nyakato', 'Ishozi', 'Kibirizi'
            ],
            'Ngara' => [
                'Nyamiaga', 'Rulenge', 'Ngara', 'Murusagamba', 'Kanazi'
            ],
            'Karagwe' => [
                'Omurushaka', 'Nyakakika', 'Bugene', 'Ndama', 'Kituntu'
            ],
            'Misenyi' => [
                'Kanyigo', 'Mutukula', 'Bugandika', 'Minziro', 'Bwanjai'
            ],
            'Biharamulo' => [
                'Nyakahura', 'Runazi', 'Lusahunga', 'Nyarubungo', 'Kabindi'
            ],
            'Katavi' => [
                'Mpanda Ndogo', 'Kapalamsenga', 'Ikola', 'Katumba', 'Sitalike'
            ],
            'Mpanda' => [
                'Mpanda Ndogo', 'Mwese', 'Kabungu', 'Mishamo', 'Inyonga'
            ],
            'Nsimbo' => [
                'Karema', 'Kasokola', 'Sitalike', 'Kakese', 'Ifukutwa'
            ],
            'Kigoma Urban' => [
                'Kigoma', 'Gungu', 'Bangwe', 'Kitongoni', 'Mlole'
            ],
            'Kigoma Rural' => [
                'Mahembe', 'Mwandiga', 'Kasangezi', 'Kizenga', 'Simbo'
            ],
            'Buhigwe' => [
                'Mugera', 'Kajana', 'Muyama', 'Munzeze', 'Nyamugali'
            ],
            'Uvinza' => [
                'Nguruka', 'Kazuramimba', 'Ilagala', 'Basanza', 'Sigunga'
            ],
            'Kibondo' => [
                'Kibondo', 'Kasanda', 'Biturana', 'Mabamba', 'Kifura'
            ],
            'Kasulu' => [
                'Kasulu Mjini', 'Nyansha', 'Heru Juu', 'Manyovu', 'Rugongwe'
            ],
            'Moshi Urban' => [
                'Bondeni', 'Rau', 'Majengo', 'Msaranga', 'Kiboriloni'
            ],
            'Moshi Rural' => [
                'Mwika', 'Marangu', 'Kirua', 'Kahe', 'Old Moshi'
            ],
            'Hai' => [
                'Machame', 'Masama', 'Weruweru', 'Sanya Juu', 'Kilimanjaro'
            ],
            'Rombo' => [
                'Holili', 'Tarakea', 'Uchira', 'Ngoyoni', 'Makiidi'
            ],
            'Same' => [
                'Ndungu', 'Makanya', 'Msindo', 'Kisima', 'Vudee'
            ],
            'Kilimanjaro' => [
                'Mwika Kusini', 'Mwika Kaskazini', 'Uru Mashariki', 'Shiri', 'Kilema'
            ],
            'Lindi Urban' => [
                'Mitandi', 'Mwenge', 'Nachingwea', 'Makonde', 'Mbanja'
            ],
            'Lindi Rural' => [
                'Mtama', 'Mandawa', 'Kilimanihewa', 'Nyangao', 'Milola'
            ],
            'Kilwa' => [
                'Kilwa Masoko', 'Kivinje', 'Nanjirinji', 'Liuli', 'Somanga'
            ],
            'Ruhua' => [
                'Tandahimba', 'Mkoma', 'Namanga', 'Mtumbatu', 'Ruhua'
            ],
            'Liwale' => [
                'Barikiwa', 'Mpigamiti', 'Lilombe', 'Nangano', 'Kihuru'
            ],
            'Babati' => [
                'Gallapo', 'Magugu', 'Mamire', 'Dareda', 'Ufana'
            ],
            'Manyara' => [
                'Mbulu', 'Hanang', 'Simanjiro', 'Kiteto', 'Babati'
            ],
            'Mbulu' => [
                'Endamilay', 'Dongobesh', 'Haydom', 'Maretadu', 'Tumati'
            ],
            'Hanang' => [
                'Katesh', 'Gendabi', 'Basotu', 'Balangdalalu', 'Laghanga'
            ],
            'Simanjiro' => [
                'Orkesumet', 'Naberera', 'Ngorika', 'Ruvu Remit', 'Mererani'
            ],
            'Musoma Urban' => [
                'Bweri', 'Makoko', 'Mukendo', 'Nyamatare', 'Kamunyonge'
            ],
            'Musoma Rural' => [
                'Nyegina', 'Bukima', 'Suguti', 'Bugwema', 'Kiriba'
            ],
            'Serengeti' => [
                'Mugumu', 'Rung’abure', 'Machochwe', 'Nyambureti', 'Park Nyigoti'
            ],
            'Tarime' => [
                'Nyamwaga', 'Roche', 'Turwa', 'Nyabisaga', 'Matongo'
            ],
            'Mbeya Urban' => [
                'Iyunga', 'Ilomba', 'Nzungu', 'Mwakibete', 'Sisimba'
            ],
            'Mbeya Rural' => [
                'Igale', 'Ilembo', 'Inyala', 'Itete', 'Tembela'
            ],
            'Rungwe' => [
                'Bagamoyo', 'Bujela', 'Kiwira', 'Lufilyo', 'Masukulu'
            ],
            'Mbarali' => [
                'Rujewa', 'Igava', 'Itamboleo', 'Mapogoro', 'Mahongole'
            ],
            'Chunya' => [
                'Mkwajuni', 'Makongolosi', 'Matwiga', 'Ifumbo', 'Kambikatoto'
            ],
            'Morogoro Urban' => [
                'Kihonda', 'Mazimbu', 'Sabasaba', 'Boma', 'Bigwa'
            ],
            'Morogoro Rural' => [
                'Mkuyuni', 'Mikese', 'Melela', 'Kiroka', 'Lugono'
            ],
            'Kilombero' => [
                'Kidatu', 'Mang’ula', 'Mlimba', 'Ifakara', 'Idete'
            ],
            'Mvomero' => [
                'Mlali', 'Mgeta', 'Kinyenze', 'Mzumbe', 'Dionis'
            ],
            'Mtwara Urban' => [
                'Chuno', 'Magengeni', 'Likombe', 'Rahaleo', 'Shangani'
            ],
            'Mtwara Rural' => [
                'Nanyamba', 'Mingoyo', 'Ziwani', 'Nanguruwe', 'Chikongola'
            ],
            'Nanyumbu' => [
                'Mangaka', 'Masuguru', 'Napacho', 'Nanganga', 'Kitangari'
            ],
            'Masasi' => [
                'Mkuti', 'Namajani', 'Sindano', 'Luchingu', 'Mtwara Ndogo'
            ],
            'Newala' => [
                'Kitangari', 'Chitekete', 'Makote', 'Mkunya', 'Likombwa'
            ],
            'Tunduru' => [
                'Nakapanya', 'Ligoma', 'Nalasi', 'Mbesa', 'Nandembo'
            ],
            'Mwanza Urban' => [
                'Mirongo', 'Ilemela', 'Mabatini', 'Nyamagana', 'Kisesa'
            ],
            'Mwanza Rural' => [
                'Ilemela', 'Nyanguge', 'Kitangiri', 'Sengerema', 'Bujora'
            ],
            'Ilemela' => [
                'Mabatini', 'Mikocheni', 'Nyakato', 'Ishokela', 'Ilemela'
            ],
            'Sengerema' => [
                'Bujora', 'Sengerema Town', 'Nyamatongo', 'Mwamashimba', 'Kisesa'
            ],
            'Misungwi' => [
                'Nyang’wale', 'Misungwi Town', 'Kivuluga', 'Igalula', 'Mwajika'
            ],
            'Njombe Urban' => [
                'Majengo', 'Kantemu', 'Njombe', 'Ilembula', 'Kitanda'
            ],
            'Njombe Rural' => [
                'Wanging’ombe', 'Makambako', 'Matamba', 'Mdamba', 'Madaba'
            ],
            'Makambako' => [
                'Mawengi', 'Ilembula', 'Nyololo', 'Matola', 'Lwenge'
            ],
            'Wanging’ombe' => [
                'Bai', 'Wanging’ombe Town', 'Lufingo', 'Idete', 'Manda'
            ],
            'Ludewa' => [
                'Ludewa Town', 'Idodi', 'Nkomang’ombe', 'Ngeleka', 'Lundo'
            ],
            'Wete' => [
                'Sumbu', 'Lulindi', 'Wete Town', 'Mtambile', 'Mangapwani'
            ],
            'Mkoani' => [
                'Mkoani Town', 'Lindi', 'Chumbuni', 'Mgombezi', 'Moro'
            ],
            'Chake Chake' => [
                'Chake Town', 'Makunduchi', 'Dole', 'Pemba', 'Bumbuli'
            ],
            'Pemba' => [
                'Makiungu', 'Wete', 'Pemba Town', 'Bumbuli', 'Pandani'
            ],
            'Pwani' => [
                'Mafia', 'Bagamoyo', 'Kibaha', 'Rufiji', 'Mafia Town'
            ],
            'Bagamoyo' => [
                'Bagamoyo Town', 'Bohari', 'Mchinjio', 'Kinondoni', 'Magogoni'
            ],
            'Kibaha' => [
                'Kibaha Town', 'Mlandizi', 'Kibaha', 'Ikwiriri', 'Gonja'
            ],
            'Mafia' => [
                'Mafia Town', 'Kilindoni', 'Kibiti', 'Nguruma', 'Rufiji'
            ],
            'Rufiji' => [
                'Kibiti', 'Mafia', 'Rufiji Town', 'Wami', 'Mwadui'
            ],
            'Sumbawanga Urban' => [
                'Sumbawanga Town', 'Maboga', 'Luwumbu', 'Sakala', 'Kapenta'
            ],
            'Sumbawanga Rural' => [
                'Sumbawanga', 'Nanjilinji', 'Momba', 'Mwimbi', 'Kigongo'
            ],
            'Nkasi' => [
                'Nkasi Town', 'Rukwa', 'Kiwira', 'Mbala', 'Isanga'
            ],
            'Kalambo' => [
                'Kalambo Town', 'Tunduma', 'Nyundo', 'Mbilima', 'Mapande'
            ],
            'Mbinga' => [
                'Mbinga Town', 'Luanda', 'Wami', 'Makonde', 'Songea'
            ],
            'Songea Urban' => [
                'Songea Town', 'Mgeni', 'Kizwite', 'Ngarama', 'Ruvuma'
            ],
            'Songea Rural' => [
                'Namtumbo', 'Mchombero', 'Maposeni', 'Makawa', 'Nyera'
            ],
            'Namtumbo' => [
                'Namtumbo Town', 'Misebe', 'Mwandiga', 'Namwenda', 'Kilosa'
            ],
            'Tunduru' => [
                'Tunduru Town', 'Madaba', 'Doma', 'Idodi', 'Nyang’wale'
            ],
            'Shinyanga Urban' => [
                'Shinyanga Town', 'Ushirika', 'Ibadakuli', 'Mwashiku', 'Maganzo'
            ],
            'Shinyanga Rural' => [
                'Shinyanga', 'Busekela', 'Nzega', 'Mwadui', 'Misugusugu'
            ],
            'Kahama' => [
                'Kahama Town', 'Nguruka', 'Ilemela', 'Igoma', 'Mbwade'
            ],
            'Busega' => [
                'Busega Town', 'Nsimbo', 'Wamang’ombe', 'Bati', 'Mufindi'
            ],
            'Simiyu' => [
                'Simiyu Town', 'Nkinga', 'Bariadi', 'Sumbawanga', 'Kaburi'
            ],
            'Bariadi' => [
                'Bariadi Town', 'Simiyu', 'Bujora', 'Buhama', 'Nyashimo'
            ],
            'Igalula' => [
                'Igalula Town', 'Songea', 'Mbinga', 'Mikumi', 'Nyambure'
            ],
            'Singida Urban' => [
                'Singida Town', 'Mungumwenge', 'Msela', 'Dutwa', 'Nduguti'
            ],
            'Singida Rural' => [
                'Singida', 'Nduguti', 'Mikumi', 'Nkinga', 'Bunge'
            ],
            'Manyoni' => [
                'Manyoni Town', 'Msela', 'Kinyeto', 'Ulemo', 'Maji'
            ],
            'Kahama' => [
                'Kahama Town', 'Kiwira', 'Mambali', 'Kahama', 'Shinyanga Town'
            ],
            'Tabora Rural' => ['Nyahua', 'Mbugani', 'Ipalamwa', 'Lundusi'], 
            'Urambo' => ['Chiponda', 'Mikumbi', 'Kahama', 'Urambo Town'],
            'Ngara' => ['Kaguma', 'Kihumuro', 'Ngara Town'],
            'Tanga Urban' => ['Mikadi', 'Mlingano', 'Nguvumali', 'Ushongo'],
            'Tanga Rural' => ['Kwelendogo', 'Soni', 'Tanga Town'],
            'Pangani' => ['Bombo', 'Kidaya', 'Pangani Town'],
            'Muheza' => ['Kizumbi', 'Muheza Town', 'Mbuyuni'],
            'Kilindi' => ['Bumbuli', 'Kilindi Town', 'Kwangwa'],
            'Kaskazini A' => ['Korogwe', 'Lushoto', 'Shambala'],
            'Kaskazini B' => ['Mashewa', 'Bombo', 'Soni'],
            'Kusini' => ['Mbuji', 'Mkinga', 'Pangani'],
            'Micheweni' => ['Micheweni Town', 'Nkwemkwe'],
            'Mjini' => ['Mjini Town', 'Mbagala'],
            'Magharibi' => ['Mombasa', 'Kisauni', 'Likoni'],
            'Nairobi City' => ['Westlands', 'Lang’ata', 'Kasarani'],
            'Lang’ata' => ['Kibera', 'Ngong Road'],
            'Westlands' => ['Parklands', 'Muthangari', 'Highlands'],
            'Kasarani' => ['Njiru', 'Roysambu', 'Kasarani Town'],
            'Embakasi' => ['Utawala', 'Mlolongo', 'Embakasi Town'],
            'Mombasa Island' => ['Old Town', 'Nyali', 'Likoni'],
            'Nyali' => ['Mombasa Town', 'Kongowea'],
            'Kisauni' => ['Mwembe Tayari', 'Mombasa Town'],
            'Likoni' => ['Shika Adabu', 'Likoni Town'],
            'Kisumu East' => ['Obunga', 'Nyamasaria', 'Kisumu Town'],
            'Kisumu West' => ['Ahero', 'Maseno', 'Londiani'],
            'Nyando' => ['Konde', 'Katito', 'Kogony'],
            'Rangwe' => ['Homa Bay', 'Rangwe Town'],
            'Nakuru Town East' => ['Menengai', 'Lanet', 'Nakuru Town'],
            'Nakuru Town West' => ['Mokowe', 'Shabaab'],
            'Naivasha' => ['Naivasha Town', 'Nakuru Town'],
            'Gilgil' => ['Gilgil Town', 'Njiru'],
            'Eldoret East' => ['Kapsaret', 'Kapsoya', 'Eldoret Town'],
            'Eldoret West' => ['Soy', 'Turbo'],
            'Kapseret' => ['Kapseret Town', 'Kapkures'],
            'Soy' => ['Soy Town', 'Harambee'],
            "Nyeri Town" => ["Central Ward", "Kamakwa Ward", "Kingongo Ward"],
            "Mathira" => ["Miharati Ward", "Karatina Ward", "Kangocho Ward"],
            "Othaya" => ["Othaya Ward", "Mukurwe-ini Ward", "Nyeri South Ward"],
            "Kieni" => ["Kieni East Ward", "Kieni West Ward"],
            "Machakos Town" => ["Machakos Ward", "Katoloni Ward"],
            "Masinga" => ["Masinga Ward", "Kathiani Ward"],
            "Kangundo" => ["Kangundo Ward", "Mbiu Ward"],
            "Matungulu" => ["Matungulu Ward", "Mavoko Ward"],
            "Meru Town" => ["Nyambene Ward", "Nchiru Ward"],
            "Imenti North" => ["Imenti North Ward", "Abothuguchi Ward"],
            "Imenti South" => ["Imenti South Ward", "Magumoni Ward"],
            "Buuri" => ["Buuri Ward", "Nthimbiri Ward"],
            "Thika Town" => ["Thika Ward", "Landhies Ward", "Kimbo Ward"],
            "Ruiru" => ["Ruiru Ward", "Kamiti Ward"],
            "Gatanga" => ["Gatanga Ward", "Kangundo Ward"],
            "Kiambu" => ["Kiambu Ward", "Gikambura Ward"],
            "Kitale Town" => ["Kitale Ward", "Chesegem Ward"],
            "Kiminini" => ["Kiminini Ward", "Bunyala Ward"],
            "Cherangany" => ["Cherangany Ward", "Kwanza Ward"],
            "Endebess" => ["Endebess Ward", "Sitatunga Ward"],
            "Malindi Town" => ["Malindi Ward", "Mnarani Ward"],
            "Ganze" => ["Ganze Ward", "Mwatate Ward"],
            "Kilifi North" => ["Kilifi Ward", "Chonyi Ward"],
            "Kilifi South" => ["Kilifi South Ward", "Marafa Ward"],
            "Garissa Town" => ["Garissa Ward", "Balambala Ward"],
            "Lagdera" => ["Lagdera Ward", "Dadaab Ward"],
            "Fafi" => ["Fafi Ward", "Waberi Ward"],
            "Dadaab" => ["Dadaab Ward", "Hulugho Ward"],
            "Kakamega Town" => ["Kakamega Ward", "Shinyalu Ward"],
            "Lugari" => ["Lugari Ward", "Lumakanda Ward"],
            "Butere" => ["Butere Ward", "Ikolomani Ward"],
            "Malava" => ["Malava Ward", "Shikusa Ward"],
            "Bungoma Town" => ["Bungoma Ward", "Webuye West Ward"],
            "Webuye" => ["Webuye Ward", "Bungoma South Ward"],
            "Sirisia" => ["Sirisia Ward", "Bumula Ward"],
            "Chwele" => ["Chwele Ward", "Kimilili Ward"],
            "Kiambu Town" => ["Kiambu Town Ward", "Thika Ward"],
            "Gikambura" => ["Gikambura Ward", "Limuru Ward"],
            "Moyale Town" => ["Moyale Ward", "North Horr Ward"],
            "North Horr" => ["North Horr Ward", "Sololo Ward"],
            "Sololo" => ["Sololo Ward", "Moyale Ward"],
            "Naivasha" => ["Naivasha Ward", "Karagita Ward"],
            "Gilgil" => ["Gilgil Ward", "Njiru Ward"],
            "Njoro" => ["Njoro Ward", "Molo Ward"],
            "Lamu Town" => ["Lamu Ward", "Witu Ward"],
            "Hulugho" => ["Hulugho Ward", "Garissa South Ward"],
            "Witu" => ["Witu Ward", "Lamu South Ward"],
            "Mandera Town" => ["Mandera Ward", "Kotulo Ward"],
            "Banisa" => ["Banisa Ward"],
            "Lafey" => ["Lafey Ward"],
            "Takaba" => ["Takaba Ward"],
            "Narok North" => ["Narok Ward", "Nararwai Ward", "Kehancha Ward"],
            "Narok South" => ["Ololunga Ward", "Narok Town", "Enkare Narok Ward"],
            "Narok East" => ["Narok East Ward", "Olepito Ward", "Nkaroni Ward"],
            "Narok West" => ["Narok West Ward", "Olololo Ward", "Isara Ward"],
            "Embu Town" => ["Embu Ward", "Kianjokoma Ward", "Ndururi Ward"],
            "Mbeere North" => ["Mbeere Ward", "Mwea Ward"],
            "Mbeere South" => ["Kiritiri Ward", "Muthambi Ward"],
            "Homa Bay Town" => ["Homa Bay Ward", "Kendu Bay Ward", "Ongoro Ward"],
            "Mbita" => ["Mbita Ward", "Kochia Ward", "Remba Ward"],
            "Ndhiwa" => ["Ndhiwa Ward", "Suna West Ward"],
            "Suba North" => ["Suba North Ward", "Kaksingri Ward"],
            "Suba South" => ["Suba South Ward", "Mbita Town Ward"],
            "Kericho Town" => ["Kericho Town Ward", "Sosiot Ward"],
            "Ainamoi" => ["Ainamoi Ward", "Kongotio Ward", "Tendwet Ward"],
            "Belgut" => ["Belgut Ward", "Kapsoit Ward"],
            "Kapsoit" => ["Kapsoit Ward", "Chemagel Ward"],
            "Migori Town" => ["Migori Ward", "Oruba Ward"],
            "Uriri" => ["Uriri Ward", "Mikayuni Ward"],
            "Rongo" => ["Rongo Ward", "Kuria West Ward"],
            "Nyatike" => ["Nyatike Ward", "Kehancha Ward"],
            "Nyamira Town" => ["Nyamira Ward", "Bokeira Ward"],
            "Borabu" => ["Borabu Ward", "Ibeno Ward"],
            "Nyamira" => ["Nyamira East Ward", "Nyamira West Ward"],
            "Kajiado East" => ["Kajiado East Ward", "Ongata Rongai Ward"],
            "Kajiado North" => ["Kajiado North Ward", "Olkejuado Ward"],
            "Kajiado West" => ["Kajiado West Ward", "Loitokitok Ward"],
            "Voi Town" => ["Voi Ward", "Mwatate Ward"],
            "Wundanyi" => ["Wundanyi Ward", "Mwatate Ward"],
            "Mwatate" => ["Mwatate Ward", "Voi Town Ward"],
            "Kisii Town" => ["Kisii Town Ward", "Masaba Ward"],
            "Masaba South" => ["Masaba South Ward", "Sameta Ward"],
            "Masaba North" => ["Masaba North Ward", "Nyamache Ward"],
            "Gucha" => ["Gucha Ward", "Rianjagi Ward"],
            "Kampala Central" => ["Kampala Ward", "City Centre Ward"],
            "Kawempe" => ["Kawempe Ward", "Makerere Ward"],
            "Makindye" => ["Makindye Ward", "Kibuye Ward"],
            "Lubaga" => ["Lubaga Ward", "Busega Ward"],
            "Nakawa" => ["Nakawa Ward", "Ntinda Ward"],
            "Mukono Town" => ["Mukono Town Ward", "Seeta Ward"],
            "Nangabo" => ["Nangabo Ward", "Kawoya Ward"],
            "Goma" => ["Goma Ward", "Kikaya Ward"],
            "Kanganda" => ["Kanganda Ward", "Bugembe Ward"],
            "Jinja Municipality" => ["Jinja Municipality Ward", "Jinja Town Ward"],
            "Jinja North" => ["Jinja North Ward", "Bugembe Ward"],
            "Jinja South" => ["Jinja South Ward", "Mutai Ward"],
            "Bugembe" => ["Bugembe Ward", "Buwenge Ward"],
            "Mbarara Municipality" => ["Mbarara Town Ward", "Kakoba Ward"],
            "Nyamitanga" => ["Nyamitanga Ward"],
            "Biharwe" => ["Biharwe Ward"],
            "Rwentondo" => ["Rwentondo Ward"],
            "Masaka Municipality" => ["Masaka Town Ward", "Kangulumira Ward", "Kiwanga Ward"],
            "Kalisizo" => ["Kalisizo Ward", "Kikondo Ward"],
            "Bukomansimbi" => ["Bukomansimbi Ward", "Najjera Ward"],
            "Kalungu" => ["Kalungu Town Ward", "Njeru Ward"],
            "Soroti Municipality" => ["Soroti Town Ward", "Aperure Ward", "Kamuda Ward"],
            "Kaberamaido" => ["Kaberamaido Town Ward", "Kachumbala Ward"],
            "Serere" => ["Serere Town Ward", "Olilim Ward"],
            "Ngora" => ["Ngora Town Ward", "Katakwi Ward"],
            "Mbale Municipality" => ["Mbale Town Ward", "Budadiri Ward"],
            "Budadiri" => ["Budadiri Town Ward", "Nangongera Ward"],
            "Namatala" => ["Namatala Ward", "Nalongokya Ward"],
            "Nangongera" => ["Nangongera Ward", "Bubulo Ward"],
            "Lira Municipality" => ["Lira Town Ward", "Lira East Ward", "Lira West Ward"],
            "Lira East" => ["Lira East Ward", "Oyam North Ward"],
            "Lira West" => ["Lira West Ward", "Pader Ward"],
            "Otuke" => ["Otuke Ward", "Aboke Ward"],
            "Gulu Municipality" => ["Gulu Town Ward", "Gulu West Ward", "Gulu East Ward"],
            "Gulu East" => ["Gulu East Ward", "Layibi Ward"],
            "Gulu West" => ["Gulu West Ward", "Bardege Ward"],
            "Hoima Municipality" => ["Hoima Town Ward", "Buhimba Ward", "Bujumbura Ward"],
            "Bunyangabu" => ["Bunyangabu Ward", "Kijura Ward"],
            "Kibaale" => ["Kibaale Ward", "Kitumba Ward"],
            "Kiryandongo" => ["Kiryandongo Town Ward", "Bweyale Ward"],
            "Kabale Municipality" => ["Kabale Town Ward", "Northern Ward", "Central Ward"],
            "Rubanda" => ["Rubanda Town Ward", "Kangondo Ward"],
            "Rukiga" => ["Rukiga Town Ward", "Kamwenge Ward"],
            "Kasese Municipality" => ["Kasese Town Ward", "Rwenzori Ward"],
            "Kasese" => ["Kasese Town Ward", "Mushienyi Ward"],
            "Rwenzori" => ["Rwenzori Ward", "Kibanzira Ward"],
            "Kiboga Municipality" => ["Kiboga Town Ward", "Kiwoko Ward"],
            "Kiboga" => ["Kiboga Ward", "Lwengo Ward"],
            "Mubende" => ["Mubende Town Ward", "Mabindo Ward"]
        ];
        

        // Loop through each district and seed wards
        foreach ($wards as $districtName => $wardList) {
            $district = District::where('name', $districtName)->first();

            if ($district) {
                foreach ($wardList as $wardName) {
                    Ward::updateOrCreate(
                        [
                            'name' => $wardName,
                            'district_id' => $district->id,
                        ],
                        [
                            'name' => $wardName,
                            'district_id' => $district->id,
                        ]
                    );
                }
            }
        }
    }
}
