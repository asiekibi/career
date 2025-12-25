<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Models\CertificateEducation;
use App\Models\CertificateLesson;
use App\Models\UserCertificate;
use App\Models\User;
use setasign\Fpdi\Tcpdf\Fpdi;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $certificates = Certificate::with('certificateEducations')->get();
        return view('admin.certificates', compact('certificates'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'certificate_name' => 'required|string|max:255',
            'type' => 'required|in:ders,kurs',
            'course' => 'array',
            'template_file' => 'nullable|file|mimes:pdf|max:40960', // Max 40MB
        ];
        
        // Klişeli sertifika (ders) için dersler zorunlu
        if ($request->type === 'ders') {
            $rules['course'] = 'required|array|min:1';
            $rules['course.*'] = 'required|string|max:255';
        }
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $templatePath = null;

        // Sertifika şablonu yükleme - PDF'i HTML'e çevir ve orijinal PDF'i de sakla
        if ($request->hasFile('template_file')) {
            $file = $request->file('template_file');
            
            // PDF dosyasını base64 encode et
            $pdfContent = file_get_contents($file->getRealPath());
            $base64Pdf = base64_encode($pdfContent);
            
            // HTML şablonu oluştur (PDF.js ile PDF görüntüleme)
            $htmlContent = $this->convertPdfToHtml($base64Pdf, $file->getClientOriginalName());
            
            // Klasör yoksa oluştur
            $templatesDir = storage_path('app/public/certificates/templates/');
            if (!file_exists($templatesDir)) {
                mkdir($templatesDir, 0755, true);
            }
            
            // HTML dosyasını kaydet
            $htmlFileName = time() . '_' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.html';
            $htmlPath = $templatesDir . $htmlFileName;
            file_put_contents($htmlPath, $htmlContent);
            
            // Orijinal PDF dosyasını da kaydet (indirme için)
            $pdfFileName = time() . '_' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.pdf';
            $pdfPath = $templatesDir . $pdfFileName;
            file_put_contents($pdfPath, $pdfContent);
            
            $templatePath = 'storage/certificates/templates/' . $htmlFileName;
        }

        // Sertifikayı oluştur
        $certificate = Certificate::create([
            'certificate_name' => $request->certificate_name,
            'type' => $request->type,
            'template_path' => $templatePath,
        ]);

        // Boş olmayan dersleri filtrele ve ekle
        if ($request->has('course')) {
            $validCourses = array_filter($request->course, function($courseName) {
                return !empty(trim($courseName));
            });

            foreach ($validCourses as $courseName) {
                CertificateEducation::create([
                    'certificate_id' => $certificate->id,
                    'course_name' => $courseName,
                ]);
            }
        }

        return redirect()->route('admin.certificates')->with('success', 'Sertifika başarıyla eklendi!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $certificate = Certificate::with('certificateEducations')->findOrFail($id);
        return response()->json([
            'id' => $certificate->id,
            'certificate_name' => $certificate->certificate_name,
            'type' => $certificate->type,
            'template_path' => $certificate->template_path,
            'certificateEducations' => $certificate->certificateEducations->map(function($education) {
                return [
                    'id' => $education->id,
                    'course_name' => $education->course_name
                ];
            })
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'certificate_name' => 'required|string|max:255',
            'type' => 'required|in:ders,kurs',
            'course' => 'array',
            'template_file' => 'nullable|file|mimes:pdf|max:40960', // Max 40MB
        ];
        
        // Klişeli sertifika (ders) için dersler zorunlu
        if ($request->type === 'ders') {
            $rules['course'] = 'required|array|min:1';
            $rules['course.*'] = 'required|string|max:255';
        }
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $certificate = Certificate::findOrFail($id);
        $templatePath = $certificate->template_path;

        // Sertifika şablonu yükleme - PDF'i HTML'e çevir ve orijinal PDF'i de sakla
        if ($request->hasFile('template_file')) {
            $file = $request->file('template_file');
            
            // PDF dosyasını base64 encode et
            $pdfContent = file_get_contents($file->getRealPath());
            $base64Pdf = base64_encode($pdfContent);
            
            // HTML şablonu oluştur (PDF.js ile PDF görüntüleme)
            $htmlContent = $this->convertPdfToHtml($base64Pdf, $file->getClientOriginalName());
            
            // Klasör yoksa oluştur
            $templatesDir = storage_path('app/public/certificates/templates/');
            if (!file_exists($templatesDir)) {
                mkdir($templatesDir, 0755, true);
            }
            
            // HTML dosyasını kaydet
            $htmlFileName = time() . '_' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.html';
            $htmlPath = $templatesDir . $htmlFileName;
            file_put_contents($htmlPath, $htmlContent);
            
            // Orijinal PDF dosyasını da kaydet (indirme için)
            $pdfFileName = time() . '_' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.pdf';
            $pdfPath = $templatesDir . $pdfFileName;
            file_put_contents($pdfPath, $pdfContent);
            
            $templatePath = 'storage/certificates/templates/' . $htmlFileName;
        }

        // Sertifikayı güncelle
        $certificate->update([
            'certificate_name' => $request->certificate_name,
            'type' => $request->type,
            'template_path' => $templatePath,
        ]);

        // Mevcut dersleri sil
        $certificate->certificateEducations()->delete();

        // Boş olmayan dersleri filtrele ve ekle
        if ($request->has('course')) {
            $validCourses = array_filter($request->course, function($courseName) {
                return !empty(trim($courseName));
            });

            foreach ($validCourses as $courseName) {
                CertificateEducation::create([
                    'certificate_id' => $certificate->id,
                    'course_name' => $courseName,
                ]);
            }
        }

        return redirect()->route('admin.certificates')->with('success', 'Sertifika başarıyla güncellendi!');
    }

    /**
     * get assign certificate
     */
    public function getAssignCertificate($id)
    {
        $user = User::findOrFail($id);
        $certificates = Certificate::all();
        $userCertificates = $user->userCertificates()->with(['certificate.certificateEducations', 'certificateLessons.certificateEducation'])->get();
        
        return view('admin.add-certificate', compact('user', 'certificates', 'userCertificates'));
    }

    /**
     * store assign certificate
     */
    public function storeAssignCertificate(Request $request, $id)
    {
        $request->validate([
            'certificate_id' => 'required|exists:certificates,id',
            'certificate_code' => 'nullable|string|max:255|unique:user_certificates,certificate_code',
            'register_no' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
            'content1' => 'nullable|string',
            'content2' => 'nullable|string',
            'score' => 'nullable|numeric|min:0',
            'issuer' => 'nullable|string|max:255',
            'issue_date' => 'nullable|date',
            'validity_period' => 'nullable|numeric|min:1|max:10',
        ], [
            'certificate_id.required' => 'Sertifika seçimi gereklidir.',
            'certificate_id.exists' => 'Seçilen sertifika bulunamadı.',
            'certificate_code.unique' => 'Bu sertifika kodu zaten kullanılmış.',
            'score.numeric' => 'Başarı puanı sayısal olmalıdır.',
            'score.min' => 'Başarı puanı 0\'dan küçük olamaz.',
            'validity_period.numeric' => 'Geçerlilik süresi sayısal olmalıdır.',
            'validity_period.min' => 'Geçerlilik süresi en az 1 yıl olmalıdır.',
            'validity_period.max' => 'Geçerlilik süresi en fazla 10 yıl olabilir.',
        ]);

        $user = User::findOrFail($id);
        
        // Check if the same certificate is already assigned
        $existingCertificate = $user->userCertificates()->where('certificate_id', $request->certificate_id)->first();
        
        if ($existingCertificate) {
            return redirect()->back()->with('error', 'Bu sertifika zaten atanmış!');
        }
        
        // Get certificate with its courses
        $certificate = Certificate::with('certificateEducations')->findOrFail($request->certificate_id);
        
        // Determine total score (use provided score or generate random)
        $totalScore = $request->score ?? rand(60, 100);
        
        // Sertifika şifresi
        $certificatePassword = $request->password;
        
        // Certificate code - boş ise null yap
        $certificateCode = !empty(trim($request->certificate_code)) ? $request->certificate_code : null;
        
        // Log'a sertifika şifresini ekle
        Log::info('Sertifika Oluşturuldu', [
            'user_id' => $user->id,
            'user_name' => $user->name . ' ' . $user->surname,
            'user_email' => $user->email,
            'certificate_id' => $request->certificate_id,
            'certificate_code' => $certificateCode,
            'sertifika_sifresi' => $certificatePassword,
        ]);
        
        // Assign certificate
        $userCertificate = $user->userCertificates()->create([
            'certificate_id' => $request->certificate_id,
            'certificate_code' => $certificateCode,
            'register_no' => $request->register_no,
            'password' => $certificatePassword,
            'content1' => $request->content1,
            'content2' => $request->content2,
            'achievement_score' => $totalScore,
            'issuing_institution' => $request->issuer,
            'acquisition_date' => $request->issue_date,
            'validity_period' => $request->validity_period,
            'success_score' => $totalScore,
        ]);
        
        // Distribute total score randomly among courses and save to certificate_lessons table
        if ($certificate->certificateEducations->count() > 0) {
            $courses = $certificate->certificateEducations;
            $courseCount = $courses->count();
            $remainingScore = $totalScore;
            
            // If only one course, give all points to it
            if ($courseCount == 1) {
                CertificateLesson::create([
                    'user_certificate_id' => $userCertificate->id,
                    'certificate_education_id' => $courses->first()->id,
                    'score' => $totalScore,
                ]);
            } else {
                // Assign random scores to each course (except the last one)
                for ($i = 0; $i < $courseCount - 1; $i++) {
                    // Calculate max possible score for this course (leave at least 1 point for remaining courses)
                    $maxScore = max(1, $remainingScore - ($courseCount - $i - 1));
                    // Assign random score between 1 and maxScore
                    $randomScore = rand(1, $maxScore);
                    
                    CertificateLesson::create([
                        'user_certificate_id' => $userCertificate->id,
                        'certificate_education_id' => $courses[$i]->id,
                        'score' => $randomScore,
                    ]);
                    
                    $remainingScore -= $randomScore;
                }
                
                // Assign remaining score to the last course
                CertificateLesson::create([
                    'user_certificate_id' => $userCertificate->id,
                    'certificate_education_id' => $courses[$courseCount - 1]->id,
                    'score' => max(1, $remainingScore),
                ]);
            }
        }

        // User's point value update
        $user->point = $user->point + $totalScore;
        $user->save();

        return redirect()->back()->with('success', 'Sertifika başarıyla atandı!');
    }

    /**
     * update assign certificate
     */
    public function updateAssignCertificate(Request $request, $id)
    {
        $request->validate([
            'certificate_code' => 'nullable|string|max:255|unique:user_certificates,certificate_code,' . $id,
            'register_no' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
            'content1' => 'nullable|string',
            'content2' => 'nullable|string',
            'score' => 'nullable|numeric|min:0',
            'issuer' => 'nullable|string|max:255',
            'issue_date' => 'nullable|date',
            'validity_period' => 'nullable|numeric|min:1|max:10',
        ], [
            'certificate_code.unique' => 'Bu sertifika kodu zaten kullanılmış.',
            'score.numeric' => 'Başarı puanı sayısal olmalıdır.',
            'score.min' => 'Başarı puanı 0\'dan küçük olamaz.',
            'validity_period.numeric' => 'Geçerlilik süresi sayısal olmalıdır.',
            'validity_period.min' => 'Geçerlilik süresi en az 1 yıl olmalıdır.',
            'validity_period.max' => 'Geçerlilik süresi en fazla 10 yıl olabilir.',
        ]);

        $userCertificate = UserCertificate::findOrFail($id);
        $user = $userCertificate->user;
        
        // Eski puanı kullanıcıdan çıkar
        $oldScore = $userCertificate->success_score ?? 0;
        $user->point = $user->point - $oldScore;
        
        // Yeni puanı belirle
        $newScore = $request->score ?? $oldScore;
        
        // Sertifika şifresi
        $certificatePassword = $request->password ?? $userCertificate->password;
        
        // Certificate code - boş ise null yap
        $certificateCode = !empty(trim($request->certificate_code)) ? $request->certificate_code : null;
        
        // Log'a sertifika şifresini ekle
        Log::info('Sertifika Güncellendi', [
            'user_certificate_id' => $userCertificate->id,
            'user_id' => $user->id,
            'user_name' => $user->name . ' ' . $user->surname,
            'user_email' => $user->email,
            'certificate_code' => $certificateCode,
            'sertifika_sifresi' => $certificatePassword,
        ]);
        
        // Güncellenecek alanları hazırla
        $updateData = [
            'certificate_code' => $certificateCode,
            'register_no' => $request->register_no,
            'password' => $certificatePassword,
            'content1' => $request->content1,
            'content2' => $request->content2,
            'issuing_institution' => $request->issuer,
            'achievement_score' => $newScore,
            'success_score' => $newScore,
        ];
        
        // Validity period - sadece dolu gelirse güncelle
        if ($request->filled('validity_period')) {
            $updateData['validity_period'] = $request->validity_period;
        }
        
        // Tarih alanı - sadece dolu gelirse güncelle, boş gelirse mevcut değeri koru
        if ($request->filled('issue_date')) {
            $updateData['acquisition_date'] = $request->issue_date;
        } else {
            // Boş gelirse mevcut değeri koru (null yazma)
            $updateData['acquisition_date'] = $userCertificate->acquisition_date;
        }
        
        // Sertifikayı güncelle
        $userCertificate->update($updateData);
        
        // Yeni puanı kullanıcıya ekle
        $user->point = $user->point + $newScore;
        $user->save();

        return redirect()->back()->with('success', 'Sertifika başarıyla güncellendi!');
    }

    /**
     * remove certificate
     */
    public function removeCertificate(Request $request)
    {
        $userCertificateId = $request->input('id');
        $userCertificate = UserCertificate::findOrFail($userCertificateId);
        
        // user point update
        $user = $userCertificate->user;
        $user->point = $user->point - $userCertificate->success_score;
        $user->save();
        
        $userCertificate->delete();
        return redirect()->back()->with('success', 'Sertifika başarıyla kaldırıldı!');
    }

    /**
     * download certificate
     */
    public function downloadCertificate($id)
    {
        $userCertificate = UserCertificate::with(['user', 'certificate'])->findOrFail($id);
        
        // Sertifika şablonunu veritabanından al
        $certificate = $userCertificate->certificate;
        
        if (!$certificate) {
            return redirect()->back()->with('error', 'Sertifika bulunamadı!');
        }
        
        // Sertifika tipine göre şablon dosyasını belirle
        $templatePath = null;
        $certificateType = $certificate->type ?? 'ders'; // Varsayılan olarak 'ders'
        
        // Eğer type boşsa veya yanlışsa, certificate_name'e göre kontrol et
        if (empty($certificateType) || $certificateType === 'ders') {
            $certificateName = strtolower($certificate->certificate_name ?? '');
            if (strpos($certificateName, 'kurs') !== false) {
                $certificateType = 'kurs';
            }
        }
        
        // Önce sertifika tipine göre özel şablon dosyasını kontrol et
        $typeBasedTemplatePaths = [
            'ders' => [
                storage_path('app/public/certificates/templates/ders_template.pdf'),
                storage_path('app/public/certificates/templates/ders_tasarım.pdf'),
                public_path('ders_tasarım.pdf'),
                public_path('ders_template.pdf'),
            ],
            'kurs' => [
                storage_path('app/public/certificates/templates/kurs_template.pdf'),
                storage_path('app/public/certificates/templates/kurs_tasarım.pdf'),
                public_path('kurs_tasarım.pdf'),
                public_path('kurs_template.pdf'),
            ],
        ];
        
        // Tip bazlı şablon dosyalarını kontrol et
        if (isset($typeBasedTemplatePaths[$certificateType])) {
            foreach ($typeBasedTemplatePaths[$certificateType] as $path) {
                if (file_exists($path)) {
                    $templatePath = $path;
                    break;
                }
            }
        }
        
        // Eğer tip bazlı şablon bulunamadıysa, sertifikanın kendi template_path'ini kullan
        if (!$templatePath && $certificate->template_path) {
            // HTML dosya adından PDF dosya adını oluştur
            $htmlFileName = basename($certificate->template_path);
            $pdfFileName = str_replace('.html', '.pdf', $htmlFileName);
            
            // PDF dosyasının tam yolu
            $templatePath = storage_path('app/public/certificates/templates/' . $pdfFileName);
        }
        
        // Eğer hala şablon bulunamadıysa, varsayılan şablonu kullan
        if (!$templatePath || !file_exists($templatePath)) {
            $templatePath = public_path('tasarım.pdf');
            
            if (!file_exists($templatePath)) {
                return redirect()->back()->with('error', 'Sertifika şablonu bulunamadı!');
            }
        }
        
        // FPDI ile PDF oluştur
        $pdf = new Fpdi();
        
        // UTF-8 encoding ayarları - Türkçe karakter desteği için
        $pdf->setLanguageArray([
            'a_meta_charset' => 'UTF-8',
            'a_meta_dir' => 'ltr',
            'a_meta_language' => 'tr',
            'w_page' => 'sayfa'
        ]);
        
        // Template PDF'i ekle
        $pageCount = $pdf->setSourceFile($templatePath);
        $tplId = $pdf->importPage(1);
        
        // PDF boyutunu al
        $size = $pdf->getTemplateSize($tplId);
        
        // Sayfa boyutuna göre sayfa ekle
        if ($size['width'] > $size['height']) {
            $pdf->AddPage('L', [$size['width'], $size['height']]);
        } else {
            $pdf->AddPage('P', [$size['width'], $size['height']]);
        }
        
        $pdf->useTemplate($tplId, 0, 0, $size['width'], $size['height'], true);
        
        // Kullanıcı adı soyadı (Türkçe karakter desteği ile) - İlk harfleri büyük yap ama İ harfini koru
        $name = $userCertificate->user->name;
        $surname = $userCertificate->user->surname;
        
        // Her kelimenin ilk harfini büyük yap (İ harfini koruyarak)
        $name = $this->mbUcfirst($name);
        $surname = $this->mbUcfirst($surname);
        
        $userName = $name . ' ' . $surname;
        
        // CloisterBlack fontunu yükle (Old English stili) - İstediğiniz font
        $cloisterFontName = null;
        $cloisterFontPaths = [
            public_path('fonts/cloister-black-font/CloisterBlack.ttf'),
            public_path('fonts/CloisterBlack.ttf'),
            public_path('fonts/cloister-black-font/CloisterBlackLight-axjg.ttf'),
            public_path('fonts/CloisterBlackLight-axjg.ttf'),
        ];
        
        foreach ($cloisterFontPaths as $fontPath) {
            if (file_exists($fontPath)) {
                try {
                    // TrueTypeUnicode ile font yükle - Türkçe karakter desteği için
                    $cloisterFontName = \TCPDF_FONTS::addTTFfont($fontPath, 'TrueTypeUnicode', '', 32);
                    if ($cloisterFontName) {
                        // Font yüklendi
                        break; // Font başarıyla yüklendi
                    }
                } catch (\Exception $e) {
                     continue; // Bu dosya yüklenemedi, bir sonrakini dene
                }
            }
        }
        
        // MiddleAges_PERSONAL_USE fontunu yükle - "ğ" karakteri için
        $middleAgesFontName = null;
        $middleAgesFontPaths = [
            public_path('fonts/MiddleAges_PERSONAL_USE/MiddleAges_PERSONAL_USE.ttf'),
            public_path('fonts/MiddleAges_PERSONAL_USE.ttf'),
        ];
        
        foreach ($middleAgesFontPaths as $fontPath) {
            if (file_exists($fontPath)) {
                try {
                    $middleAgesFontName = \TCPDF_FONTS::addTTFfont($fontPath, 'TrueTypeUnicode', '', 32);
                    if ($middleAgesFontName) {
                        break;
                    }
                } catch (\Exception $e) {
                     continue;
                }
            }
        }
        
        // Türkçe karakterler için fallback font (DejaVu Serif)
        $turkishFallbackFont = 'dejavuserif';
        
        
        
        // Sağ üste Register No ve içerik ekle (siyah renkte)
        $pdf->SetTextColor(0, 0, 0); // Siyah renk
        // Register No'yu user_certificates tablosundan al (register_no alanından)
        $registerNo = $userCertificate->register_no ?? '';
        // Eğer register_no boşsa, certificate_code'u kullan
        if (empty($registerNo)) {
            $registerNo = $userCertificate->certificate_code ?? 'CERT-' . str_pad($userCertificate->id, 6, '0', STR_PAD_LEFT);
        }
        // Content1 ve Content2'yi user_certificates tablosundan al
        $content1 = $userCertificate->content1 ?? '';
        $content2 = $userCertificate->content2 ?? '';
        
        // Eğer içerik 1 yoksa, içerik 2'yi içerik 1 yerine kullan
        if (empty($content1) && !empty($content2)) {
            $content1 = $content2;
            $content2 = '';
        }
        
        // İçerik var mı kontrol et
        $hasContent = !empty($content1) || !empty($content2);
        
        
        if ($certificateType === 'kurs') {
            $registerY = 26; // Template içindeki beyaz alanın başlangıcından 26mm aşağıda (24 -> 26, içerik yazılarını aşağıya taşımak için)
            $content1Y = $registerY + 4; // Register No'nun 4mm altında (1 -> 4, içerik yazısını aşağı indirmek için)
            $content2Y = $content1Y + 3; // İçerik 1'in 3mm altında
            $tarihY = $content2Y + 3; // İçerik 2'nin 3mm altında (ders: 5mm)
            $userNameYPercentage = null; // Kurs için yüzde kullanmayacağız
            $userNameYDefault = null; // Kurs için varsayılan değer kullanmayacağız
            
        } else {
            // Ders sertifikaları için mevcut pozisyonlar
            $registerY = 25; // Üstten 25mm aşağıda (30 -> 25, daha yukarı)
            $content1Y = $registerY + 5; // Register No'nun 5mm altında
            $content2Y = $content1Y + 5; // İçerik 1'in 5mm altında
            $tarihY = $content2Y + 5; // İçerik 2'nin 5mm altında
            $userNameYPercentage = 0.48; // Sayfanın %48'i kadar yukarıdan
            $userNameYDefault = 145; // Varsayılan değer
            
        }
        
        // Register No: yazısı - Harf harf font seçimi
        $registerText = 'Register No: ' . $registerNo;
        
        // Sağdan mesafe - sertifika tipine göre ayarla
        if ($certificateType === 'ders') {
            $rightMargin = 40; // Ders tipi için sağdan 40mm mesafe (45 -> 40, biraz sağa)
            $contentRightMargin = 24; // İçerik yazıları için daha sağa yakın (30 -> 24, daha sağa)
        } else {
            $rightMargin = 40; // Kurs tipi için sağdan 40mm mesafe (35 -> 40, Register No'yu sola kaydırmak için)
            $contentRightMargin = 20; // İçerik yazıları için daha sağa yakın (26 -> 20, daha sağa)
        }
        $registerX = $size['width'] - $rightMargin;
        $contentX = $size['width'] - $contentRightMargin; // İçerik yazıları için ayrı X pozisyonu
        $cellWidth = 70;
        
        // Register No yazısı - Harf harf font seçimi ile
        $registerTextLength = mb_strlen($registerText, 'UTF-8');
        $registerCharWidths = [];
        $registerFontSize = 12;
        
        // Türkçe karakterler listesi
        $turkishChars = ['ğ', 'Ğ', 'ş', 'Ş', 'ü', 'Ü', 'ö', 'Ö', 'ç', 'Ç', 'İ', 'ı'];
        // MiddleAges font'u için özel karakterler - bu karakterler için font boyutunu küçült
        $middleAgesPreferredChars = ['İ', 'ğ', 'ş', 'ü', 'ç', 'ı', 'Ğ', 'Ü', 'Ç', 'Ş'];
        // Küçük harfler için özel karakterler - bu karakterler için font boyutunu daha da küçült ve Y pozisyonunu yukarı al
        $smallCharsSpecial = ['ğ', 'ü', 'ç', 'ş', 'ı'];
        // Büyük harfler için özel karakterler - bu karakterler için font boyutunu küçült
        $bigCharsSpecial = ['Ğ', 'Ü', 'Ç', 'Ş', 'İ'];
        // Dikey hizalama sorunu olan karakterler - bu karakterler için Y pozisyonunu biraz yukarı al
        $verticalAdjustChars = ['ğ', 'ü', 'ç'];
        
        // Önce tüm karakterlerin genişliğini hesapla
        for ($i = 0; $i < $registerTextLength; $i++) {
            $char = mb_substr($registerText, $i, 1, 'UTF-8');
            $fontName = $this->getFontForCharacter($pdf, $char, $registerFontSize, $cloisterFontName, $middleAgesFontName, $turkishFallbackFont);
            
            // Küçük harfler için özel kontrol (ğ, ü, ç, ş, ı)
            $isSmallCharSpecial = in_array($char, $smallCharsSpecial, true);
            // Büyük harfler için özel kontrol (Ğ, Ü, Ç, Ş, İ)
            $isBigCharSpecial = in_array($char, $bigCharsSpecial, true);
            // MiddleAges preferred karakterler için font boyutunu küçült
            $isMiddleAgesPreferred = in_array($char, $middleAgesPreferredChars, true);
            $isUsingMiddleAges = ($middleAgesFontName && $fontName === $middleAgesFontName);
            
            // Türkçe karakter ise ve DejaVu kullanılıyorsa font boyutunu küçült
            $isTurkishChar = in_array($char, $turkishChars, true);
            
            if ($isSmallCharSpecial && $isUsingMiddleAges) {
                $actualFontSize = $registerFontSize * 0.85; // Küçük özel karakterler için %85'si kadar
            } elseif ($isBigCharSpecial && $isUsingMiddleAges) {
                $actualFontSize = $registerFontSize * 0.88; // Büyük özel karakterler için %88'si kadar
            } elseif ($isMiddleAgesPreferred && $isUsingMiddleAges) {
                $actualFontSize = $registerFontSize * 0.90; // MiddleAges preferred karakterler için %90'si kadar
            } elseif ($isTurkishChar && $fontName === $turkishFallbackFont) {
                $actualFontSize = $registerFontSize * 0.92; // DejaVu kullanılıyorsa %92'si kadar
            } else {
                $actualFontSize = $registerFontSize; // Normal boyut
            }
            
            $pdf->SetFont($fontName, '', $actualFontSize);
            $registerCharWidths[$i] = $pdf->GetStringWidth($char);
        }
        
        // Toplam genişliği hesapla ve sağa hizala
        $registerTotalWidth = array_sum($registerCharWidths);
        $registerStartX = $registerX - $registerTotalWidth;
        $registerCurrentX = $registerStartX;
        
        // Her karakteri uygun font ile yaz
        for ($i = 0; $i < $registerTextLength; $i++) {
            $char = mb_substr($registerText, $i, 1, 'UTF-8');
            $fontName = $this->getFontForCharacter($pdf, $char, $registerFontSize, $cloisterFontName, $middleAgesFontName, $turkishFallbackFont);
            
            // Küçük harfler için özel kontrol (ğ, ü, ç, ş, ı)
            $isSmallCharSpecial = in_array($char, $smallCharsSpecial, true);
            // Büyük harfler için özel kontrol (Ğ, Ü, Ç, Ş, İ)
            $isBigCharSpecial = in_array($char, $bigCharsSpecial, true);
            // MiddleAges preferred karakterler için font boyutunu küçült
            $isMiddleAgesPreferred = in_array($char, $middleAgesPreferredChars, true);
            $isUsingMiddleAges = ($middleAgesFontName && $fontName === $middleAgesFontName);
            
            // Türkçe karakter ise ve DejaVu kullanılıyorsa font boyutunu küçült
            $isTurkishChar = in_array($char, $turkishChars, true);
            
            if ($isSmallCharSpecial && $isUsingMiddleAges) {
                $actualFontSize = $registerFontSize * 0.85; // Küçük özel karakterler için %85'si kadar
            } elseif ($isBigCharSpecial && $isUsingMiddleAges) {
                $actualFontSize = $registerFontSize * 0.88; // Büyük özel karakterler için %88'si kadar
            } elseif ($isMiddleAgesPreferred && $isUsingMiddleAges) {
                $actualFontSize = $registerFontSize * 0.90; // MiddleAges preferred karakterler için %90'si kadar
            } elseif ($isTurkishChar && $fontName === $turkishFallbackFont) {
                $actualFontSize = $registerFontSize * 0.92; // DejaVu kullanılıyorsa %92'si kadar
            } else {
                $actualFontSize = $registerFontSize; // Normal boyut
            }
            
            $pdf->SetFont($fontName, '', $actualFontSize);
            
            // Küçük ğ, ü, ç için dikey pozisyonu biraz yukarı al (yukarıya mesafe)
            $adjustY = in_array($char, $verticalAdjustChars, true) ? $registerY - 0.3 : $registerY;
            
            $pdf->SetXY($registerCurrentX, $adjustY);
            $pdf->Write(10, $char, '', 0, '', false, 0, false, false, 0);
            $registerCurrentX += $registerCharWidths[$i];
        }
        
        // İçerik yazıları - sadece içerik varsa göster
        if ($hasContent) {
            $pdf->SetFont('dejavuserif', 'I', 8); // Küçük italik font
            
            // İçerik yazıları için ortalama genişlik (Register No ile aynı hizada ama ortalanmış)
            $contentCellWidth = $cellWidth;
            // İçerik yazılarının ortalanacağı X pozisyonu (Register No ile aynı sağdan mesafe)
            $contentCenterX = $size['width'] - $contentRightMargin - ($contentCellWidth / 2);
            
            // İçerik 1 yazısı (Register No'nun altında) - Ortalanmış
            if (!empty($content1)) {
                $pdf->SetXY($contentCenterX - ($contentCellWidth / 2), $content1Y);
                $pdf->Cell($contentCellWidth, 10, $content1, 0, 0, 'C'); // 'C' = Center (ortalanmış)
            }
            
            // İçerik 2 yazısı (İçerik 1'in altında) - Ortalanmış
            if (!empty($content2)) {
                $pdf->SetXY($contentCenterX - ($contentCellWidth / 2), $content2Y);
                $pdf->Cell($contentCellWidth, 10, $content2, 0, 0, 'C'); // 'C' = Center (ortalanmış)
            }
        }
        
        // Tarih yazısı kaldırıldı
        
        $pdf->SetTextColor(0, 0, 0); // Siyah renk
        
        // Ad Soyad için font boyutu
        // Kurs tipinde karakter sayısına göre dinamik font boyutu
        $userNameLength = mb_strlen($userName, 'UTF-8');
        if ($certificateType === 'kurs') {
            if ($userNameLength <= 21) {
                $fontSize = 40; // 21 karakter ve altı: 40
            } elseif ($userNameLength <= 25) {
                $fontSize = 36; // 22-25 karakter: 36
            } elseif ($userNameLength <= 30) {
                $fontSize = 32; // 26-30 karakter: 32
            } else {
                $fontSize = 28; // 31+ karakter: 28
            }
        } else {
            $fontSize = 40; // Ders tipi için her zaman 40
        }
        
        $x = 0;
        
        if ($certificateType === 'kurs') {
            $cellHeight = 10;
            $bottomMargin = 118; // 122 -> 118 (biraz daha aşağıya taşımak için azaltıldı)
            if (isset($size['height']) && $size['height'] > 0) {
                $y = $size['height'] - $cellHeight - $bottomMargin;
            } else {
                $y = 152; // 148 -> 152 (biraz daha aşağıya taşımak için artırıldı)
            }
        } else {
            if (isset($size['height']) && $size['height'] > 0) {
                $y = $size['height'] * 0.46; // 0.48 -> 0.46 (daha yukarı)
            } else {
                $y = 138; // 143 -> 138 (daha yukarı)
            }
        }
        
        // Ad Soyad yazısı - Harf harf font seçimi ile
        $pdf->SetTextColor(0, 0, 0);
        
        // Harf harf font seçimi ile yaz
        $this->writeTextWithCharacterFonts($pdf, $userName, 0, $y, $fontSize, $cloisterFontName, $middleAgesFontName, $turkishFallbackFont, $size['width'], $certificateType);
        
        $pdf->SetCreator('Kariyer Sistemi');
        $pdf->SetAuthor('Kariyer Sistemi');
        $pdf->SetTitle($certificate->certificate_name ?? 'Sertifika');
        $pdf->SetSubject('Sertifika');
        
        // PDF şifre koruması - Her iki sertifika tipi (ders ve kurs) için de uygulanır"""
        $password = $userCertificate->password ?? '12345';
        $pdf->SetProtection(
            ['print', 'modify', 'copy', 'annot-forms'],
            $password,
            $password
        );
        
        $pdfContent = $pdf->Output('', 'S');
        
        $fileName = 'Sertifika_' . preg_replace('/[^a-zA-Z0-9_\-]/', '_', $userCertificate->user->name) . '_' . preg_replace('/[^a-zA-Z0-9_\-]/', '_', $userCertificate->user->surname) . '.pdf';
        
        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->header('Cache-Control', 'private, must-revalidate');
    }

    /**
     * Bir karakter için uygun fontu seç (harf harf kontrol)
     * Sıra: CloisterBlack -> MiddleAges -> DejaVu
     * Eğer bir font'ta karakter bulunamazsa bir sonraki font'a geçer
     */
    private function getFontForCharacter($pdf, $char, $fontSize, $cloisterFontName, $middleAgesFontName, $turkishFallbackFont)
    {
        // Büyük İ, küçük ğ, küçük ş, küçük ü, küçük ç, küçük ı ve büyük harfler (Ğ, Ü, Ç, Ş, İ) için direkt MiddleAges font'unu kullan (eğer yüklüyse)
        $middleAgesPreferredChars = ['İ', 'ğ', 'ş', 'ü', 'ç', 'ı', 'Ğ', 'Ü', 'Ç', 'Ş'];
        if (in_array($char, $middleAgesPreferredChars, true) && $middleAgesFontName) {
            try {
                $pdf->SetFont($middleAgesFontName, '', $fontSize);
                $charWidth = $pdf->GetStringWidth($char);
                
                // MiddleAges'te karakter varsa direkt kullan
                if ($charWidth > 0) {
                    $spaceWidth = $pdf->GetStringWidth(' ');
                    // Karakter genişliği boşluktan farklıysa (yani karakter gerçekten varsa) kullan
                    if (abs($charWidth - $spaceWidth) > 0.01) {
                        return $middleAgesFontName;
                    }
                }
            } catch (\Exception $e) {
                // MiddleAges yüklenemezse devam et
            }
        }
        
        // Tüm karakterler için (ğ, Ğ, İ, ı dahil) önce gothic font'ları dene
        $fontsToTry = [];
        
        if ($cloisterFontName) {
            $fontsToTry[] = $cloisterFontName;
        }
        if ($middleAgesFontName) {
            $fontsToTry[] = $middleAgesFontName;
        }
        // Son olarak fallback font'u ekle
        $fontsToTry[] = $turkishFallbackFont;
        
        // Problematik Türkçe karakterler - Bu karakterler için çok sıkı kontrol yap
        // (ğ, Ğ, ü, Ü, İ, ı gibi karakterler gothic font'larda genelde hoş görünmüyor)
        $problematicTurkishChars = ['ğ', 'Ğ', 'ü', 'Ü', 'İ', 'ı'];
        $isProblematicTurkishChar = in_array($char, $problematicTurkishChars, true);
        
        // Diğer Türkçe karakterler
        $otherTurkishChars = ['ş', 'Ş', 'ö', 'Ö', 'ç', 'Ç'];
        $isOtherTurkishChar = in_array($char, $otherTurkishChars, true);
        
        // Her font'u dene
        foreach ($fontsToTry as $fontName) {
            try {
                $pdf->SetFont($fontName, '', $fontSize);
                $charWidth = $pdf->GetStringWidth($char);
                
                // Karakter genişliği kontrolü - 0'dan büyük olmalı
                if ($charWidth > 0) {
                    // Boşluk karakterinin genişliğini al
                    $spaceWidth = $pdf->GetStringWidth(' ');
                    
                    // Problematik Türkçe karakterler için çok sıkı kontrol
                    // Bu karakterler gothic font'larda genelde hoş görünmediği için
                    // sadece gerçekten iyi görünüyorsa kullan
                    if ($isProblematicTurkishChar) {
                        // Font boyutunun %12'si kadar minimum genişlik bekliyoruz (daha sıkı)
                        $minWidth = $fontSize * 0.12;
                        
                        // Karakter genişliği boşluk genişliğinden çok belirgin şekilde farklı olmalı
                        // ve minimum genişlikten büyük olmalı
                        if (abs($charWidth - $spaceWidth) > ($fontSize * 0.10) && $charWidth >= $minWidth) {
                            return $fontName;
                        }
                    } elseif ($isOtherTurkishChar) {
                        // Diğer Türkçe karakterler için orta seviye kontrol
                        // Font boyutunun %8'i kadar minimum genişlik
                        $minWidth = $fontSize * 0.08;
                        
                        // Karakter genişliği boşluk genişliğinden belirgin şekilde farklı olmalı
                        if (abs($charWidth - $spaceWidth) > ($fontSize * 0.05) && $charWidth >= $minWidth) {
                            return $fontName;
                        }
                    } else {
                        // Normal karakterler için normal kontrol
                        // Font boyutunun %3'ü kadar minimum genişlik
                        $minWidth = $fontSize * 0.03;
                        
                        if (abs($charWidth - $spaceWidth) > 0.01 && $charWidth >= $minWidth) {
                            return $fontName;
                        }
                    }
                }
            } catch (\Exception $e) {
                // Font yüklenemezse bir sonraki font'a geç
                continue;
            }
        }
        
        // Hiçbir font'ta bulunamazsa fallback font kullan
        return $turkishFallbackFont;
    }
    
    /**
     * Metni harf harf yaz (her harf için uygun font seçimi ile)
     */
    private function writeTextWithCharacterFonts($pdf, $text, $x, $y, $fontSize, $cloisterFontName, $middleAgesFontName, $turkishFallbackFont, $width, $certificateType = 'ders')
    {
        $textLength = mb_strlen($text, 'UTF-8');
        $charWidths = [];
        
        // Türkçe karakterler listesi
        $turkishChars = ['ğ', 'Ğ', 'ş', 'Ş', 'ü', 'Ü', 'ö', 'Ö', 'ç', 'Ç', 'İ', 'ı'];
        // MiddleAges font'u için özel karakterler - bu karakterler için font boyutunu küçült
        $middleAgesPreferredChars = ['İ', 'ğ', 'ş', 'ü', 'ç', 'ı', 'Ğ', 'Ü', 'Ç', 'Ş'];
        // Küçük harfler için özel karakterler - bu karakterler için font boyutunu daha da küçült
        $smallCharsSpecial = ['ğ', 'ü', 'ç', 'ş', 'ı'];
        // Büyük harfler için özel karakterler - bu karakterler için font boyutunu küçült
        $bigCharsSpecial = ['Ğ', 'Ü', 'Ç', 'Ş', 'İ'];
        // Dikey hizalama sorunu olan karakterler - bu karakterler için Y pozisyonunu biraz aşağı al (ad soyad için)
        $verticalAdjustChars = ['ğ', 'ü', 'ç', 'ş', 'ı', 'Ç', 'İ', 'Ş'];
        
        // Ders tipi için özel ayarlar
        $isDersType = ($certificateType === 'ders');
        
        // Önce tüm karakterlerin genişliğini hesapla
        for ($i = 0; $i < $textLength; $i++) {
            $char = mb_substr($text, $i, 1, 'UTF-8');
            $fontName = $this->getFontForCharacter($pdf, $char, $fontSize, $cloisterFontName, $middleAgesFontName, $turkishFallbackFont);
            
            // Küçük harfler için özel kontrol (ğ, ü, ç, ş, ı)
            $isSmallCharSpecial = in_array($char, $smallCharsSpecial, true);
            // Büyük harfler için özel kontrol (Ğ, Ü, Ç, Ş, İ)
            $isBigCharSpecial = in_array($char, $bigCharsSpecial, true);
            // MiddleAges preferred karakterler için font boyutunu küçült
            $isMiddleAgesPreferred = in_array($char, $middleAgesPreferredChars, true);
            $isUsingMiddleAges = ($middleAgesFontName && $fontName === $middleAgesFontName);
            
            // Türkçe karakter ise ve DejaVu kullanılıyorsa font boyutunu küçült
            $isTurkishChar = in_array($char, $turkishChars, true);
            
            // Font boyutu ayarları - Her iki tip için de aynı
            if ($isSmallCharSpecial && $isUsingMiddleAges) {
                $actualFontSize = $fontSize * 0.71; // Küçük özel karakterler %71'si kadar (her iki tip için)
            } elseif ($isBigCharSpecial && $isUsingMiddleAges) {
                $actualFontSize = $fontSize * 0.75; // Büyük özel karakterler %75'si kadar (her iki tip için)
            } elseif ($isMiddleAgesPreferred && $isUsingMiddleAges) {
                $actualFontSize = $fontSize * 0.90; // MiddleAges preferred karakterler için %90'si kadar
            } elseif ($isTurkishChar && $fontName === $turkishFallbackFont) {
                $actualFontSize = $fontSize * 0.92; // DejaVu kullanılıyorsa %92'si kadar
            } else {
                $actualFontSize = $fontSize; // Normal boyut
            }
            
            $pdf->SetFont($fontName, '', $actualFontSize);
            $charWidths[$i] = $pdf->GetStringWidth($char);
        }
        
        // Harfler arası boşluk - font boyutunun %2'si kadar
        $letterSpacing = $fontSize * 0.02;
        
        // Toplam genişliği hesapla (harfler arası boşluklar dahil)
        $totalWidth = array_sum($charWidths);
        // Son karakter hariç, her karakter arasına boşluk ekle
        if ($textLength > 1) {
            $totalWidth += $letterSpacing * ($textLength - 1);
        }
        $startX = ($width - $totalWidth) / 2;
        $currentX = $startX;
        
        // Her karakteri uygun font ile yaz
        for ($i = 0; $i < $textLength; $i++) {
            $char = mb_substr($text, $i, 1, 'UTF-8');
            $fontName = $this->getFontForCharacter($pdf, $char, $fontSize, $cloisterFontName, $middleAgesFontName, $turkishFallbackFont);
            
            // Küçük harfler için özel kontrol (ğ, ü, ç, ş, ı)
            $isSmallCharSpecial = in_array($char, $smallCharsSpecial, true);
            // Büyük harfler için özel kontrol (Ğ, Ü, Ç, Ş, İ)
            $isBigCharSpecial = in_array($char, $bigCharsSpecial, true);
            // MiddleAges preferred karakterler için font boyutunu küçült
            $isMiddleAgesPreferred = in_array($char, $middleAgesPreferredChars, true);
            $isUsingMiddleAges = ($middleAgesFontName && $fontName === $middleAgesFontName);
            
            // Türkçe karakter ise ve DejaVu kullanılıyorsa font boyutunu küçült
            $isTurkishChar = in_array($char, $turkishChars, true);
            
            // Font boyutu ayarları - Her iki tip için de aynı
            if ($isSmallCharSpecial && $isUsingMiddleAges) {
                $actualFontSize = $fontSize * 0.71; // Küçük özel karakterler %71'si kadar (her iki tip için)
            } elseif ($isBigCharSpecial && $isUsingMiddleAges) {
                $actualFontSize = $fontSize * 0.75; // Büyük özel karakterler %75'si kadar (her iki tip için)
            } elseif ($isMiddleAgesPreferred && $isUsingMiddleAges) {
                $actualFontSize = $fontSize * 0.90; // MiddleAges preferred karakterler için %90'si kadar
            } elseif ($isTurkishChar && $fontName === $turkishFallbackFont) {
                $actualFontSize = $fontSize * 0.92; // DejaVu kullanılıyorsa %92'si kadar
            } else {
                $actualFontSize = $fontSize; // Normal boyut
            }
            
            $pdf->SetFont($fontName, '', $actualFontSize);
            
            // Küçük ğ, ü, ç, ş, ı ve büyük Ç, İ, Ş için dikey pozisyonu biraz aşağı al (diğer harflere göre)
            // Her iki tip için de aynı ayarlar
            if (in_array($char, $verticalAdjustChars, true)) {
                // Büyük harfler için biraz daha az ayar (Ç, İ, Ş)
                if (in_array($char, ['Ç', 'İ', 'Ş'], true)) {
                    $adjustY = $y + 2.0; // Büyük harfler 2.0mm aşağı (her iki tip için)
                } else {
                    $adjustY = $y + 3.0; // Küçük harfler 3.0mm aşağı (her iki tip için)
                }
            } else {
                $adjustY = $y; // Normal pozisyon
            }
            
            $pdf->SetXY($currentX, $adjustY);
            $pdf->Write(10, $char, '', 0, '', false, 0, false, false, 0);
            // Karakter genişliği + harfler arası boşluk (son karakter hariç)
            $currentX += $charWidths[$i];
            if ($i < $textLength - 1) {
                $currentX += $letterSpacing;
            }
        }
    }

    /**
     * Türkçe karakterleri koruyarak her kelimenin ilk harfini büyük yap
     */
    private function mbUcfirst($string)
    {
        if (empty($string)) {
            return $string;
        }
        
        // Metni kelimelere böl
        $words = preg_split('/\s+/', $string);
        $result = [];
        
        foreach ($words as $word) {
            if (empty($word)) {
                $result[] = $word;
                continue;
            }
            
            // İlk karakteri al
            $firstChar = mb_substr($word, 0, 1, 'UTF-8');
            $rest = mb_substr($word, 1, null, 'UTF-8');
            
            // İlk karakteri büyük yap (Türkçe karakterleri özel olarak işle)
            if ($firstChar === 'i') {
                $firstCharUpper = 'İ'; // Küçük i -> Büyük İ
            } elseif ($firstChar === 'ı') {
                $firstCharUpper = 'I'; // Küçük ı -> Büyük I
            } elseif ($firstChar === 'İ') {
                $firstCharUpper = 'İ'; // Büyük İ -> İ (koru)
            } elseif ($firstChar === 'I') {
                $firstCharUpper = 'I'; // Büyük I -> I (koru)
            } else {
                // Diğer karakterler için normal büyük harf yap
                $firstCharUpper = mb_strtoupper($firstChar, 'UTF-8');
            }
            
            $result[] = $firstCharUpper . $rest;
        }
        
        return implode(' ', $result);
    }

    /**
     * PDF'i HTML formatına çevir
     */
    private function convertPdfToHtml($base64Pdf, $originalFileName)
    {
        $html = '<!DOCTYPE html>
        <html lang="tr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Sertifika Şablonu - ' . htmlspecialchars($originalFileName) . '</title>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
                <style>
                    body {
                        margin: 0;
                        padding: 20px;
                        background: #f5f5f5;
                        font-family: Arial, sans-serif;
                    }
                    .pdf-container {
                        max-width: 100%;
                        margin: 0 auto;
                        background: white;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                        padding: 20px;
                    }
                    .pdf-page {
                        position: relative;
                        margin: 0 auto;
                        background: white;
                    }
                    .pdf-page canvas {
                        display: block;
                        width: 100%;
                        height: auto;
                    }
                    .loading {
                        text-align: center;
                        padding: 40px;
                        color: #666;
                    }
                </style>
            </head>
            <body>
                <div class="pdf-container">
                    <div id="pdf-pages" class="loading">PDF yükleniyor...</div>
                </div>

                <script>
                    // PDF.js worker ayarı
                    pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js";
                    
                    // Base64 PDF verisi
                    const base64Pdf = "' . $base64Pdf . '";
                    
                    // Base64\'ü Uint8Array\'e çevir
                    const pdfData = atob(base64Pdf);
                    const pdfBytes = new Uint8Array(pdfData.length);
                    for (let i = 0; i < pdfData.length; i++) {
                        pdfBytes[i] = pdfData.charCodeAt(i);
                    }
                    
                    // PDF\'i yükle ve render et
                    pdfjsLib.getDocument({ data: pdfBytes }).promise.then(function(pdfDoc) {
                        const pdfPages = document.getElementById("pdf-pages");
                        pdfPages.innerHTML = "";
                        
                        // Tüm sayfaları render et
                        for (let pageNum = 1; pageNum <= pdfDoc.numPages; pageNum++) {
                            pdfDoc.getPage(pageNum).then(function(page) {
                                const viewport = page.getViewport({ scale: 1.5 });
                                
                                const pageDiv = document.createElement("div");
                                pageDiv.className = "pdf-page";
                                pageDiv.style.width = viewport.width + "px";
                                pageDiv.style.height = viewport.height + "px";
                                
                                const canvas = document.createElement("canvas");
                                const context = canvas.getContext("2d");
                                canvas.height = viewport.height;
                                canvas.width = viewport.width;
                                
                                const renderContext = {
                                    canvasContext: context,
                                    viewport: viewport
                                };
                                
                                page.render(renderContext).promise.then(function() {
                                    pageDiv.appendChild(canvas);
                                    pdfPages.appendChild(pageDiv);
                                });
                            });
                        }
                    }).catch(function(error) {
                        document.getElementById("pdf-pages").innerHTML = "<div class=\'loading\'>PDF yüklenirken bir hata oluştu: " + error.message + "</div>";
                    });
                </script>
            </body>
        </html>';

        return $html;
    }

    /**
     * Sertifika şablonunu PDF formatında indir
     */
    public function downloadTemplate($id)
    {
        $certificate = Certificate::findOrFail($id);
        
        if (!$certificate->template_path) {
            return redirect()->route('admin.certificates')
                ->with('error', 'Sertifika şablonu bulunamadı.');
        }

        // HTML dosya adından PDF dosya adını oluştur
        $htmlFileName = basename($certificate->template_path);
        $pdfFileName = str_replace('.html', '.pdf', $htmlFileName);
        
        // Önce orijinal PDF dosyasını kontrol et
        $pdfPath = storage_path('app/public/certificates/templates/' . $pdfFileName);
        
        if (file_exists($pdfPath)) {
            // Orijinal PDF dosyasını oku
            $pdfContent = file_get_contents($pdfPath);
            
            // Orijinal PDF dosya adını al
            $originalFileName = $certificate->certificate_name . '_template.pdf';
            $originalFileName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $originalFileName);
            
            // PDF'i response olarak döndür
            return response($pdfContent, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $originalFileName . '"')
                ->header('Content-Length', strlen($pdfContent))
                ->header('Cache-Control', 'private, must-revalidate');
        }
        
        // Eğer PDF dosyası yoksa, HTML'den base64 çıkar (geriye dönük uyumluluk için)
        $htmlPath = storage_path('app/public/certificates/templates/' . $htmlFileName);
        
        if (!file_exists($htmlPath)) {
            return redirect()->route('admin.certificates')
                ->with('error', 'Şablon dosyası bulunamadı.');
        }

        // HTML dosyasını oku
        $htmlContent = file_get_contents($htmlPath);
        
        // HTML içindeki base64 PDF verisini çıkar
        // Regex'i daha esnek hale getir - tek tırnak veya çift tırnak olabilir
        preg_match('/const\s+base64Pdf\s*=\s*["\']([^"\']+)["\'];/', $htmlContent, $matches);
        
        if (!isset($matches[1]) || empty($matches[1])) {
            // Alternatif regex dene
            preg_match('/base64Pdf\s*=\s*["\']([^"\']+)["\']/', $htmlContent, $matches);
        }
        
        if (!isset($matches[1]) || empty($matches[1])) {
            return redirect()->route('admin.certificates')
                ->with('error', 'PDF verisi bulunamadı. HTML dosyası bozuk olabilir.');
        }

        $base64Pdf = $matches[1];
        
        // Base64'ü decode et
        $pdfContent = base64_decode($base64Pdf, true);
        
        if ($pdfContent === false) {
            return redirect()->route('admin.certificates')
                ->with('error', 'PDF verisi decode edilemedi.');
        }
        
        // Orijinal PDF dosya adını al
        $originalFileName = $certificate->certificate_name . '_template.pdf';
        $originalFileName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $originalFileName);
        
        // PDF'i response olarak döndür
        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $originalFileName . '"')
            ->header('Content-Length', strlen($pdfContent))
            ->header('Cache-Control', 'private, must-revalidate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $certificate = Certificate::findOrFail($id);
            
            // Sertifikanın kullanıcılara atanıp atanmadığını kontrol et
            $userCertificates = UserCertificate::where('certificate_id', $id)->get();
            $userCertificatesCount = $userCertificates->count();
            
            // Eğer kullanıcılara atanmışsa, önce kullanıcılardan kaldır
            if ($userCertificatesCount > 0) {
                // Her bir kullanıcı sertifikası için certificate_lessons kayıtlarını sil
                foreach ($userCertificates as $userCertificate) {
                    CertificateLesson::where('user_certificate_id', $userCertificate->id)->delete();
                }
                
                // Kullanıcı sertifikalarını sil
                UserCertificate::where('certificate_id', $id)->delete();
            }
            
            // Sertifika eğitimlerini sil
            CertificateEducation::where('certificate_id', $id)->delete();
            
            // Şablon dosyasını sil (eğer varsa)
            if ($certificate->template_path) {
                $templatePath = public_path($certificate->template_path);
                if (file_exists($templatePath)) {
                    unlink($templatePath);
                }
                
                // PDF dosyasını da sil (eğer varsa)
                $pdfPath = str_replace('.html', '.pdf', $templatePath);
                if (file_exists($pdfPath)) {
                    unlink($pdfPath);
                }
            }
            
            // Sertifikayı sil
            $certificate->delete();
            
            $message = 'Sertifika başarıyla silindi.';
            if ($userCertificatesCount > 0) {
                $message .= " ({$userCertificatesCount} kullanıcıdan otomatik olarak kaldırıldı.)";
            }
            
            return redirect()->route('admin.certificates')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            Log::error('Sertifika silme hatası: ' . $e->getMessage());
            return redirect()->route('admin.certificates')
                ->with('error', 'Sertifika silinirken bir hata oluştu: ' . $e->getMessage());
        }
    }

}