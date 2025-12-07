<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobListing;

class JobListingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobListing::create([
            'job_title' => 'Pilates Eğitmeni',
            'job_description' => 'Fitness merkezimizde görev alacak, deneyimli ve sertifikalı Pilates eğitmeni arıyoruz. Adayların en az 2 yıl deneyime sahip olması ve uluslararası geçerli Pilates sertifikası bulunması gerekmektedir. Grup dersleri ve özel dersler verme konusunda yetkin olmalıdır. Müşteri memnuniyetine önem veren, iletişim becerileri güçlü, dinamik ve pozitif enerjiye sahip kişiler tercih edilecektir. Haftalık çalışma saatleri esnek olup, maaş deneyime göre belirlenecektir.',
            'phone' => '0555 123 45 67',
        ]);
    }
}







