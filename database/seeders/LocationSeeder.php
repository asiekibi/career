<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Türkiye'nin ID'sini bul
        $turkey = Country::where('code', 'TR')->first();
        
        if (!$turkey) {
            throw new \Exception('Türkiye ülkesi bulunamadı. Önce CountrySeeder çalıştırılmalı.');
        }

        $turkeyId = $turkey->id;

        // JSON dosyasını oku
        $jsonPath = public_path('tr.json');
        $jsonData = File::get($jsonPath);
        $data = json_decode($jsonData, true);

        foreach ($data as $city) {
            // İl kaydını oluştur (parent_id = 0, city_id = ilin kendi ID'si)
            $cityRecord = Location::create([
                'parent_id' => 0,
                'location' => $city['text'],
                'city_id' => $city['value'],
                'country_id' => $turkeyId
            ]);

            // İlçeleri ekle (parent_id = 1, city_id = ilin value'si)
            if (isset($city['districts']) && is_array($city['districts'])) {
                foreach ($city['districts'] as $district) {
                    Location::create([
                        'parent_id' => 1, // Sabit 1 değeri
                        'location' => $district['text'],
                        'city_id' => $city['value'], // İlin JSON'daki value'si
                        'country_id' => $turkeyId
                    ]);
                }
            }
        }
    }
}