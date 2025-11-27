<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin kullanıcısı
        User::create([
            'register_number' => $this->generateUniqueRegisterNumber(),
            'name' => 'Admin',
            'surname' => 'User',
            'email' => 'admin@kariyer.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'gender' => 'man',
            'role' => 'admin',
            'birth_date' => '1990-01-15',
            'gsm' => '05551234567',
            'point' => '0',
            'location_id' => '1',
            'district_id' => '1',
            'contact_info' => true,
            'profile_photo_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuA-AWicWCmp5DD_BDRcV7DOhqw0M1RpXUg-zNjAExRWK1NsNUu5LwjwYYg2lyriudBCqxmCBUak9yXwg4MlpYHgnC35wvqYtEbIK58IVpIx5wsd531ZXjNxs_-HOn5SSsJBJoHvBB_GjtFbffKAdG3v8vvc3pI3mrCQTcQuSu-O0ZVvMpQQSBpgXzMRx9uVHwEo8mJsACCSeBZQroBhC9yB2pq44ENUoZ8tGNofb4Pr1VkshyRpvPxUPVbB4vBnwKaWHmGFF4v4d58',
            'is_active' => true,
        ]);

        // Test kullanıcısı
        User::create([
            'register_number' => $this->generateUniqueRegisterNumber(),
            'name' => 'Test',
            'surname' => 'User',
            'email' => 'test@kariyer.com',
            'email_verified_at' => now(),
            'password' => Hash::make('test123'),
            'gender' => 'woman',
            'role' => 'user',
            'birth_date' => '1995-05-20',
            'gsm' => '05559876543',
            'point' => '0',
            'location_id' => '2',
            'district_id' => '2',
            'contact_info' => true,
            'profile_photo_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDpNDfV4gTAlcZeA_jy11IcrVcXO6eEMN43tBSoFJW6DVMuIhnkBoAGtPUry6YuudrPyGQwRgxhJbdyskpIsjSHGGV7E0pQA5ew8vOEiHgnWGVOhGCb_yWyBs3YQ98M2T6QTAC713wospvj8BGUwCqbe0bXvQgVyWCVrzUlvMesIWpjDQUgQHK0IGGWitrMri0tg8kI38x31dKBr99IGyxPoemeMQBYkCY2uH1ucVZ8kAi7dm9yKE77lv6KqNhkFmyShCINvMaN0bY',
            'is_active' => true,
        ]);

        // Demo kullanıcısı
        User::create([
            'register_number' => $this->generateUniqueRegisterNumber(),
            'name' => 'Demo',
            'surname' => 'Kullanıcı',
            'email' => 'demo@kariyer.com',
            'email_verified_at' => now(),
            'password' => Hash::make('demo123'),
            'gender' => 'other',
            'role' => 'user',
            'birth_date' => '1988-12-10',
            'gsm' => '05555555555',
            'point' => '0',
            'location_id' => '3',
            'district_id' => '3',
            'contact_info' => false,
            'profile_photo_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCoZKaVu3h9OLYhvfs_ziBQUK7yDvWH6dquhU3RLFdlf0mUbR1qOca6kn8M4IF80o7STpw8mOJrbe42X7CDFnNXtkh8BBD3peBQijFiQoNjD_c4FRqT_2Y3eeWCJFjgatIHrvc07BDFa8q0EbJC6QUYwHYyB-N2Xygk-fNPvs8wXdntLvdXrtJoMepD8ftpYKt7-3ULXHLpb-yykuCaMMyNGKKGP_mgX9nIAHgw5GWeUJzx4w1Js8qF3GoM6YQS1riAngqYTb_ee4U',
            'is_active' => false,
        ]);

        // Geliştirici kullanıcısı
        User::create([
            'register_number' => $this->generateUniqueRegisterNumber(),
            'name' => 'Developer',
            'surname' => 'Dev',
            'email' => 'dev@kariyer.com',
            'email_verified_at' => now(),
            'password' => Hash::make('dev123'),
            'gender' => 'man',
            'role' => 'admin',
            'birth_date' => '1992-08-25',
            'gsm' => '05551111111',
            'point' => '0',
            'location_id' => '1',
            'district_id' => '1',
            'contact_info' => true,
            'profile_photo_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBTIc5c0DSBehmD3OBQorQ5--xC4lmEgbgeWMEnx1lwnPcWye3yFyacsK80y9Pirm33T73OtBWQGrRdA_9OtoQbNmSRyEIH6rGb1bixANq2UpTNoMTuC7kz0FZujY8w5sknn_V8ipq_3gML_j5PdO0QaJtCGTovBt9BMlUEtVdRK3yDMTgyivXb_QezKNWttz7lrGNKFzcIju9GpDPMI-kfBJOFTIrFx-ILfRpgiZRnTSr2IbrfwPthrrobrK1JXKi_2q_fvbGycv0',
            'is_active' => true,
        ]);

        // Yönetici kullanıcısı
        User::create([
            'register_number' => $this->generateUniqueRegisterNumber(),
            'name' => 'Manager',
            'surname' => 'Manager',
            'email' => 'manager@kariyer.com',
            'email_verified_at' => now(),
            'password' => Hash::make('manager123'),
            'gender' => 'woman',
            'role' => 'admin',
            'birth_date' => '1985-03-18',
            'gsm' => '05552222222',
            'point' => '0',
            'location_id' => '2',
            'district_id' => '2',
            'contact_info' => true,
            'profile_photo_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuD-sJ7HsgrLwrclUBjxKigDAERUcpLBb9IolzMNCVVG-T_pD2c4LNjvIr06_Uj92vxW1NhCWaIK4k2kjkl64uLJvUfV0VL_q7u2SqLeEQWgHucoB-BWWMuneRxk03TvL3NsYV5flS7vEL04f-3x0uHLy3pe_uB7WTBp0gLDY-mlaJzj7aU2sfePuc7DajHjGwFIiCARETI3UpOvGJEIUYuzta1Gs1AKunJCeiWe7Cz87hXjtjg-OexlG8mrZF2Ios74dNJSfGB9_9U',
            'is_active' => true,
        ]);

        // 10 Öğrenci kullanıcısı
        $students = [
            [
                'name' => 'Zeynep',
                'surname' => 'Yılmaz',
                'email' => 'zeynep.yilmaz@kariyer.com',
                'gender' => 'woman',
                'birth_date' => '1998-03-15',
                'gsm' => '05551234567',
                'point' => '0',
                'location_id' => '1',
                'district_id' => '1',
                'contact_info' => true,
                'profile_photo_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDpNDfV4gTAlcZeA_jy11IcrVcXO6eEMN43tBSoFJW6DVMuIhnkBoAGtPUry6YuudrPyGQwRgxhJbdyskpIsjSHGGV7E0pQA5ew8vOEiHgnWGVOhGCb_yWyBs3YQ98M2T6QTAC713wospvj8BGUwCqbe0bXvQgVyWCVrzUlvMesIWpjDQUgQHK0IGGWitrMri0tg8kI38x31dKBr99IGyxPoemeMQBYkCY2uH1ucVZ8kAi7dm9yKE77lv6KqNhkFmyShCINvMaN0bY',
                'is_active' => true,
            ],
            [
                'name' => 'Emir',
                'surname' => 'Demir',
                'email' => 'emir.demir@kariyer.com',
                'gender' => 'man',
                'birth_date' => '1999-07-22',
                'gsm' => '05559876543',
                'point' => '0',
                'location_id' => '2',
                'district_id' => '2',
                'contact_info' => false,
                'profile_photo_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCoZKaVu3h9OLYhvfs_ziBQUK7yDvWH6dquhU3RLFdlf0mUbR1qOca6kn8M4IF80o7STpw8mOJrbe42X7CDFnNXtkh8BBD3peBQijFiQoNjD_c4FRqT_2Y3eeWCJFjgatIHrvc07BDFa8q0EbJC6QUYwHYyB-N2Xygk-fNPvs8wXdntLvdXrtJoMepD8ftpYKt7-3ULXHLpb-yykuCaMMyNGKKGP_mgX9nIAHgw5GWeUJzx4w1Js8qF3GoM6YQS1riAngqYTb_ee4U',
                'is_active' => false,
            ],
            [
                'name' => 'Elif',
                'surname' => 'Çelik',
                'email' => 'elif.celik@kariyer.com',
                'gender' => 'woman',
                'birth_date' => '2000-11-08',
                'gsm' => '05555555555',
                'point' => '0',
                'location_id' => '3',
                'district_id' => '3',
                'contact_info' => true,
                'profile_photo_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBTIc5c0DSBehmD3OBQorQ5--xC4lmEgbgeWMEnx1lwnPcWye3yFyacsK80y9Pirm33T73OtBWQGrRdA_9OtoQbNmSRyEIH6rGb1bixANq2UpTNoMTuC7kz0FZujY8w5sknn_V8ipq_3gML_j5PdO0QaJtCGTovBt9BMlUEtVdRK3yDMTgyivXb_QezKNWttz7lrGNKFzcIju9GpDPMI-kfBJOFTIrFx-ILfRpgiZRnTSr2IbrfwPthrrobrK1JXKi_2q_fvbGycv0',
                'is_active' => true,
            ],
            [
                'name' => 'Yusuf',
                'surname' => 'Şahin',
                'email' => 'yusuf.sahin@kariyer.com',
                'gender' => 'man',
                'birth_date' => '1997-04-12',
                'gsm' => '05551111111',
                'point' => '0',
                'location_id' => '1',
                'district_id' => '1',
                'contact_info' => false,
                'profile_photo_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAiZy7_0sBZJFkpCWmTTb5dP7ArsSrprUhyLd5DdumZpJOXRfCpKxdIz7Fxsf8uUbLN6hcq_eyj6TSntrOWFM2O8xaDvH1hBK03bMxGqMfWql8o1ISCFBUTRLPPj7p_rSQCZOiudfWqW32IRURann1NVkDoOlJLe62wCT9KFkyTIMxXWYburwaPhsyFWhkEmHJZzAzhGThP9z3ACJYm2mVsCi6FL3-D1uCtlto_zBwQP7bS0-nInWp6CybUUZgYdFJEjkGtt35y1q0',
                'is_active' => false,
            ],
            [
                'name' => 'Fatma',
                'surname' => 'Aydın',
                'email' => 'fatma.aydin@kariyer.com',
                'gender' => 'woman',
                'birth_date' => '1996-09-30',
                'gsm' => '05552222222',
                'point' => '0',
                'location_id' => '2',
                'district_id' => '2',
                'contact_info' => true,
                'profile_photo_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuD-sJ7HsgrLwrclUBjxKigDAERUcpLBb9IolzMNCVVG-T_pD2c4LNjvIr06_Uj92vxW1NhCWaIK4k2kjkl64uLJvUfV0VL_q7u2SqLeEQWgHucoB-BWWMuneRxk03TvL3NsYV5flS7vEL04f-3x0uHLy3pe_uB7WTBp0gLDY-mlaJzj7aU2sfePuc7DajHjGwFIiCARETI3UpOvGJEIUYuzta1Gs1AKunJCeiWe7Cz87hXjtjg-OexlG8mrZF2Ios74dNJSfGB9_9U',
                'is_active' => true,
            ],
            [
                'name' => 'Ahmet',
                'surname' => 'Kaya',
                'email' => 'ahmet.kaya@kariyer.com',
                'gender' => 'man',
                'birth_date' => '1999-01-18',
                'gsm' => '05553333333',
                'point' => '0',
                'location_id' => '3',
                'district_id' => '3',
                'contact_info' => false,
                'profile_photo_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAiZy7_0sBZJFkpCWmTTb5dP7ArsSrprUhyLd5DdumZpJOXRfCpKxdIz7Fxsf8uUbLN6hcq_eyj6TSntrOWFM2O8xaDvH1hBK03bMxGqMfWql8o1ISCFBUTRLPPj7p_rSQCZOiudfWqW32IRURann1NVkDoOlJLe62wCT9KFkyTIMxXWYburwaPhsyFWhkEmHJZzAzhGThP9z3ACJYm2mVsCi6FL3-D1uCtlto_zBwQP7bS0-nInWp6CybUUZgYdFJEjkGtt35y1q0',
                'is_active' => false,
            ],
            [
                'name' => 'Ayşe',
                'surname' => 'Özkan',
                'email' => 'ayse.ozkan@kariyer.com',
                'gender' => 'woman',
                'birth_date' => '1998-06-25',
                'gsm' => '05554444444',
                'point' => '0',
                'location_id' => '1',
                'district_id' => '1',
                'contact_info' => true,
                'profile_photo_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDpNDfV4gTAlcZeA_jy11IcrVcXO6eEMN43tBSoFJW6DVMuIhnkBoAGtPUry6YuudrPyGQwRgxhJbdyskpIsjSHGGV7E0pQA5ew8vOEiHgnWGVOhGCb_yWyBs3YQ98M2T6QTAC713wospvj8BGUwCqbe0bXvQgVyWCVrzUlvMesIWpjDQUgQHK0IGGWitrMri0tg8kI38x31dKBr99IGyxPoemeMQBYkCY2uH1ucVZ8kAi7dm9yKE77lv6KqNhkFmyShCINvMaN0bY',
                'is_active' => true,
            ],
            [
                'name' => 'Mehmet',
                'surname' => 'Yıldız',
                'email' => 'mehmet.yildiz@kariyer.com',
                'gender' => 'man',
                'birth_date' => '1997-12-03',
                'gsm' => '05555555555',
                'point' => '0',
                'location_id' => '2',
                'district_id' => '2',
                'contact_info' => false,
                'profile_photo_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCoZKaVu3h9OLYhvfs_ziBQUK7yDvWH6dquhU3RLFdlf0mUbR1qOca6kn8M4IF80o7STpw8mOJrbe42X7CDFnNXtkh8BBD3peBQijFiQoNjD_c4FRqT_2Y3eeWCJFjgatIHrvc07BDFa8q0EbJC6QUYwHYyB-N2Xygk-fNPvs8wXdntLvdXrtJoMepD8ftpYKt7-3ULXHLpb-yykuCaMMyNGKKGP_mgX9nIAHgw5GWeUJzx4w1Js8qF3GoM6YQS1riAngqYTb_ee4U',
                'is_active' => false,
            ],
            [
                'name' => 'Selin',
                'surname' => 'Arslan',
                'email' => 'selin.arslan@kariyer.com',
                'gender' => 'woman',
                'birth_date' => '2000-08-14',
                'gsm' => '05556666666',
                'point' => '0',
                'location_id' => '3',
                'district_id' => '3',
                'contact_info' => true,
                'profile_photo_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBTIc5c0DSBehmD3OBQorQ5--xC4lmEgbgeWMEnx1lwnPcWye3yFyacsK80y9Pirm33T73OtBWQGrRdA_9OtoQbNmSRyEIH6rGb1bixANq2UpTNoMTuC7kz0FZujY8w5sknn_V8ipq_3gML_j5PdO0QaJtCGTovBt9BMlUEtVdRK3yDMTgyivXb_QezKNWttz7lrGNKFzcIju9GpDPMI-kfBJOFTIrFx-ILfRpgiZRnTSr2IbrfwPthrrobrK1JXKi_2q_fvbGycv0',
                'is_active' => true,
            ],
            [
                'name' => 'Can',
                'surname' => 'Doğan',
                'email' => 'can.dogan@kariyer.com',
                'gender' => 'man',
                'birth_date' => '1999-05-07',
                'gsm' => '05557777777',
                'point' => '0',
                'location_id' => '1',
                'district_id' => '1',
                'contact_info' => true,
                'profile_photo_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAiZy7_0sBZJFkpCWmTTb5dP7ArsSrprUhyLd5DdumZpJOXRfCpKxdIz7Fxsf8uUbLN6hcq_eyj6TSntrOWFM2O8xaDvH1hBK03bMxGqMfWql8o1ISCFBUTRLPPj7p_rSQCZOiudfWqW32IRURann1NVkDoOlJLe62wCT9KFkyTIMxXWYburwaPhsyFWhkEmHJZzAzhGThP9z3ACJYm2mVsCi6FL3-D1uCtlto_zBwQP7bS0-nInWp6CybUUZgYdFJEjkGtt35y1q0',
                'is_active' => true,
            ],
        ];

        foreach ($students as $student) {
            User::create([
                'name' => $student['name'],
                'surname' => $student['surname'],
                'email' => $student['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('student123'),
                'gender' => $student['gender'],
                'role' => 'user',
                'birth_date' => $student['birth_date'],
                'gsm' => $student['gsm'],
                'register_number' => $this->generateUniqueRegisterNumber(),
                'point' => $student['point'],
                'location_id' => $student['location_id'],
                'district_id' => $student['district_id'],
                'contact_info' => $student['contact_info'],
                'profile_photo_url' => $student['profile_photo_url'],
                'is_active' => $student['is_active'],
            ]);
        }
    }

    /**
     * Veritabanında olmayan benzersiz register numarası oluştur
     */
    private function generateUniqueRegisterNumber(): string
    {
        do {
            // 8 haneli rastgele numara oluştur
            $registerNumber = str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
            
            // Veritabanında bu numara var mı kontrol et
            $exists = User::where('register_number', $registerNumber)->exists();
        } while ($exists); // Varsa yeni numara oluştur
        
        return $registerNumber;
    }
}
