<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // JSON dosyasını oku
        $jsonPath = public_path('all_countries.json');
        $jsonData = File::get($jsonPath);
        $data = json_decode($jsonData, true);

        foreach ($data as $code => $country) {
            Country::create([
                'code' => $code,
                'name' => $country['name_tr'],
            ]);
        }
    }
}

