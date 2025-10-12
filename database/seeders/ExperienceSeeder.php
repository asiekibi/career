<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cv;
use App\Models\Experience;

class ExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Test kullanıcısı için deneyimler
        $testUser = User::where('email', 'test@kariyer.com')->first();
        if ($testUser) {
            $testCv = $testUser->cvs()->first();
            if (!$testCv) {
                $testCv = $testUser->cvs()->create([
                    'resume' => 'Test kullanıcısının CV\'si',
                    'hobbies' => 'Yazılım geliştirme, kitap okuma'
                ]);
            }

            // Test kullanıcısı için deneyimler
            $testExperiences = [
                [
                    'company_name' => 'TechCorp A.Ş.',
                    'position' => 'Yazılım Geliştirici',
                    'start_date' => '2022-01-15',
                    'end_date' => '2023-06-30',
                    'description' => 'Web uygulamaları geliştirme ve bakım süreçlerinde aktif rol aldım. Laravel ve Vue.js teknolojileri kullandım.'
                ],
                [
                    'company_name' => 'StartupXYZ',
                    'position' => 'Full Stack Developer',
                    'start_date' => '2023-07-01',
                    'end_date' => null, // Hala çalışıyor
                    'description' => 'E-ticaret platformu geliştirme projesinde yer aldım. React, Node.js ve MongoDB kullandım.'
                ]
            ];

            foreach ($testExperiences as $experience) {
                $testCv->experiences()->create($experience);
            }
        }

        // Demo kullanıcısı için deneyimler
        $demoUser = User::where('email', 'demo@kariyer.com')->first();
        if ($demoUser) {
            $demoCv = $demoUser->cvs()->first();
            if (!$demoCv) {
                $demoCv = $demoUser->cvs()->create([
                    'resume' => 'Demo kullanıcısının CV\'si',
                    'hobbies' => 'Müzik, spor'
                ]);
            }

            $demoExperiences = [
                [
                    'company_name' => 'Digital Agency',
                    'position' => 'Frontend Developer',
                    'start_date' => '2021-03-01',
                    'end_date' => '2022-12-31',
                    'description' => 'Responsive web tasarımları ve kullanıcı arayüzü geliştirme konularında çalıştım.'
                ]
            ];

            foreach ($demoExperiences as $experience) {
                $demoCv->experiences()->create($experience);
            }
        }

        // Diğer kullanıcılar için rastgele deneyimler
        $otherUsers = User::where('email', '!=', 'test@kariyer.com')
                          ->where('email', '!=', 'demo@kariyer.com')
                          ->where('role', 'user')
                          ->get();

        $sampleExperiences = [
            [
                'company_name' => 'Yazılım Şirketi A',
                'position' => 'Junior Developer',
                'start_date' => '2023-01-01',
                'end_date' => null,
                'description' => 'Yazılım geliştirme süreçlerinde yer aldım.'
            ],
            [
                'company_name' => 'Teknoloji B',
                'position' => 'Backend Developer',
                'start_date' => '2022-06-01',
                'end_date' => '2023-05-31',
                'description' => 'API geliştirme ve veritabanı yönetimi konularında çalıştım.'
            ],
            [
                'company_name' => 'Startup C',
                'position' => 'DevOps Engineer',
                'start_date' => '2023-03-01',
                'end_date' => null,
                'description' => 'Bulut altyapısı ve CI/CD süreçleri üzerinde çalıştım.'
            ]
        ];

        foreach ($otherUsers as $user) {
            $userCv = $user->cvs()->first();
            if (!$userCv) {
                $userCv = $user->cvs()->create([
                    'resume' => $user->name . ' ' . $user->surname . ' CV\'si',
                    'hobbies' => 'Genel hobiler'
                ]);
            }

            // Her kullanıcı için 1-2 rastgele deneyim
            $randomExperiences = array_rand($sampleExperiences, rand(1, 2));
            if (!is_array($randomExperiences)) {
                $randomExperiences = [$randomExperiences];
            }

            foreach ($randomExperiences as $index) {
                $userCv->experiences()->create($sampleExperiences[$index]);
            }
        }
    }
}
