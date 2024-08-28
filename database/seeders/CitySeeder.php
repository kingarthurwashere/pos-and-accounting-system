<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\City;

class CitySeeder extends Seeder
{
    protected $cities = [
        ['name' => 'Harare', 'slug' => 'harare', 'active' => 1],
        ['name' => 'Bulawayo', 'slug' => 'bulawayo', 'active' => 1],
        ['name' => 'Chitungwiza', 'slug' => 'chitungwiza', 'active' => 1],
        ['name' => 'Mutare', 'slug' => 'mutare', 'active' => 1],
        ['name' => 'Gweru', 'slug' => 'gweru', 'active' => 1],
        ['name' => 'Epworth', 'slug' => 'epworth', 'active' => 0],
        ['name' => 'Kwekwe', 'slug' => 'kwekwe', 'active' => 0],
        ['name' => 'Kadoma', 'slug' => 'kadoma', 'active' => 0],
        ['name' => 'Masvingo', 'slug' => 'masvingo', 'active' => 0],
        ['name' => 'Chinhoyi', 'slug' => 'chinhoyi', 'active' => 0],
        ['name' => 'Norton', 'slug' => 'norton', 'active' => 0],
        ['name' => 'Marondera', 'slug' => 'marondera', 'active' => 0],
        ['name' => 'Ruwa', 'slug' => 'ruwa', 'active' => 0],
        ['name' => 'Chegutu', 'slug' => 'chegutu', 'active' => 0],
        ['name' => 'Zvishavane', 'slug' => 'zvishavane', 'active' => 0],
        ['name' => 'Bindura', 'slug' => 'bindura', 'active' => 0],
        ['name' => 'Beitbridge', 'slug' => 'beitbridge', 'active' => 0],
        ['name' => 'Redcliff', 'slug' => 'redcliff', 'active' => 0],
        ['name' => 'Victoria Falls', 'slug' => 'victoria-falls', 'active' => 1],
        ['name' => 'Hwange', 'slug' => 'hwange', 'active' => 0],
        ['name' => 'Gwanda', 'slug' => 'gwanda', 'active' => 0],
        ['name' => 'Kariba', 'slug' => 'kariba', 'active' => 0],
        ['name' => 'Karoi', 'slug' => 'karoi', 'active' => 0],
        ['name' => 'Chipinge', 'slug' => 'chipinge', 'active' => 0],
        ['name' => 'Gokwe', 'slug' => 'gokwe', 'active' => 0],
        ['name' => 'Shurugwi', 'slug' => 'shurugwi', 'active' => 0],
        ['name' => 'Mashava', 'slug' => 'mashava', 'active' => 0],
        ['name' => 'Chiredzi', 'slug' => 'chiredzi', 'active' => 0],
        ['name' => 'Banket', 'slug' => 'banket', 'active' => 0],
        ['name' => 'Mutoko', 'slug' => 'mutoko', 'active' => 0],
        ['name' => 'Murehwa', 'slug' => 'murehwa', 'active' => 0],
        ['name' => 'Inyati', 'slug' => 'inyati', 'active' => 0],
        ['name' => 'Mhangura', 'slug' => 'mhangura', 'active' => 0],
        ['name' => 'Lupane', 'slug' => 'lupane', 'active' => 0],
        ['name' => 'Chivhu', 'slug' => 'chivhu', 'active' => 0],
        ['name' => 'Nyanga', 'slug' => 'nyanga', 'active' => 0],
        ['name' => 'Tsholotsho', 'slug' => 'tsholotsho', 'active' => 0],
        ['name' => 'Glendale', 'slug' => 'glendale', 'active' => 0],
        ['name' => 'Mutorashanga', 'slug' => 'mutorashanga', 'active' => 0],
        ['name' => 'Rusape', 'slug' => 'rusape', 'active' => 0],
        ['name' => 'Chirundu', 'slug' => 'chirundu', 'active' => 0],
        ['name' => 'Darwendale', 'slug' => 'darwendale', 'active' => 0],
        ['name' => 'Dete', 'slug' => 'dete', 'active' => 0],
        ['name' => 'Doma', 'slug' => 'doma', 'active' => 0],
        ['name' => 'Eiffel Flats', 'slug' => 'eiffel-flats', 'active' => 0],
        ['name' => 'Esigodini', 'slug' => 'esigodini', 'active' => 0],
        ['name' => 'Filabusi', 'slug' => 'filabusi', 'active' => 0],
        ['name' => 'Guruve', 'slug' => 'guruve', 'active' => 0],
        ['name' => 'Hauna', 'slug' => 'hauna', 'active' => 0],
        ['name' => 'Headlands', 'slug' => 'headlands', 'active' => 0],
        ['name' => 'Hippo Valley', 'slug' => 'hippo-valley', 'active' => 0],
        ['name' => 'Insiza', 'slug' => 'insiza', 'active' => 0],
        ['name' => 'Kamativi', 'slug' => 'kamativi', 'active' => 0],
        ['name' => 'Kanyemba', 'slug' => 'kanyemba', 'active' => 0],
        ['name' => 'Lalapanzi', 'slug' => 'lalapanzi', 'active' => 0],
        ['name' => 'Mabvuku', 'slug' => 'mabvuku', 'active' => 0],
        ['name' => 'Magunje', 'slug' => 'magunje', 'active' => 0],
        ['name' => 'Mahusekwa', 'slug' => 'mahusekwa', 'active' => 0],
        ['name' => 'Makuti', 'slug' => 'makuti', 'active' => 0],
        ['name' => 'Maphisa', 'slug' => 'maphisa', 'active' => 0],
        ['name' => 'Mazowe', 'slug' => 'mazowe', 'active' => 0],
        ['name' => 'Mkoba', 'slug' => 'mkoba', 'active' => 0],
        ['name' => 'Mubaira', 'slug' => 'mubaira', 'active' => 0],
        ['name' => 'Murewa', 'slug' => 'murewa', 'active' => 0],
        ['name' => 'Mvurwi', 'slug' => 'mvurwi', 'active' => 0],
        ['name' => 'Ngezi', 'slug' => 'ngezi', 'active' => 0],
        ['name' => 'Nyamapanda', 'slug' => 'nyamapanda', 'active' => 0],
        ['name' => 'Odzi', 'slug' => 'odzi', 'active' => 0],
        ['name' => 'Penhalonga', 'slug' => 'penhalonga', 'active' => 0],
        ['name' => 'Plumtree', 'slug' => 'plumtree', 'active' => 0],
        ['name' => 'Raffingora', 'slug' => 'raffingora', 'active' => 0],
        ['name' => 'Selous', 'slug' => 'selous', 'active' => 0],
        ['name' => 'Shamva', 'slug' => 'shamva', 'active' => 0],
        ['name' => 'Shangani', 'slug' => 'shangani', 'active' => 0],
        ['name' => 'Soti-Source', 'slug' => 'soti-source', 'active' => 0],
        ['name' => 'Tengwe', 'slug' => 'tengwe', 'active' => 0],
        ['name' => 'Trelawney', 'slug' => 'trelawney', 'active' => 0],
        ['name' => 'Triangle', 'slug' => 'triangle', 'active' => 0],
        ['name' => 'Turk Mine', 'slug' => 'turk-mine', 'active' => 0],
        ['name' => 'Umguza', 'slug' => 'umguza', 'active' => 0],
        ['name' => 'Wedza', 'slug' => 'wedza', 'active' => 0],
        ['name' => 'Zaka', 'slug' => 'zaka', 'active' => 0]
    ];


    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //DB::table('cities')->insert($this->cities);

        //$activeCities = array_filter($this->cities, 'filterActiveCities');

        foreach ($this->cities as $city) {
            City::create([
                'name' => $city['name'],
                'slug' => $city['slug'],
                'active' => $city['active'],
            ]);
        }
    }

    // Define a callback function for the array_filter
    public function filterActiveCities($city)
    {
        return $city['active'] == 1;
    }
}
