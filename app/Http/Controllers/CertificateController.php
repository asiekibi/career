<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use App\Models\CertificateEducation;
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
        $validator = Validator::make($request->all(), [
            'certificate_name' => 'required|string|max:255',
            'type' => 'required|in:ders,kurs',
            'course' => 'array',
            'template_file' => 'nullable|file|mimes:pdf|max:10240', // Max 10MB
        ]);

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
        $validator = Validator::make($request->all(), [
            'certificate_name' => 'required|string|max:255',
            'type' => 'required|in:ders,kurs',
            'course' => 'array',
            'template_file' => 'nullable|file|mimes:pdf|max:10240', // Max 10MB
        ]);

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
        $userCertificates = $user->userCertificates()->with('certificate')->get();
        
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
        
        // Assign certificate
        $userCertificate = $user->userCertificates()->create([
            'certificate_id' => $request->certificate_id,
            'certificate_code' => $request->certificate_code,
            'register_no' => $request->register_no,
            'password' => $request->password,
            'content1' => $request->content1,
            'content2' => $request->content2,
            'achievement_score' => $request->score ?? 0,
            'issuing_institution' => $request->issuer,
            'acquisition_date' => $request->issue_date,
            'validity_period' => $request->validity_period,
            'success_score' => $request->score ?? 0,
        ]);

        // User's point value update
        $user->point = $user->point + ($request->score ?? 0);
        $user->save();

        return redirect()->back()->with('success', 'Sertifika başarıyla atandı!');
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
        
        // Kullanıcı adı soyadı (Türkçe karakter desteği ile) - Sadece ilk harfler büyük (Title Case)
        $userName = mb_convert_case($userCertificate->user->name . ' ' . $userCertificate->user->surname, MB_CASE_TITLE, 'UTF-8');
        
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
        $content1 = $userCertificate->content1 ?? $certificate->content ?? '16 Hours Theoretical';
        $content2 = $userCertificate->content2 ?? '';
        
        
        if ($certificateType === 'kurs') {
            $registerY = 25; // Template içindeki beyaz alanın başlangıcından 25mm aşağıda (daha aşağı)
            $content1Y = $registerY + 3; // Register No'nun 3mm altında (ders: 5mm)
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
        
        // Register No: yazısı - CloisterBlack fontu kullan
        $registerFontName = $cloisterFontName ? $cloisterFontName : $turkishFallbackFont;
        $pdf->SetFont($registerFontName, '', 12);
        // Sağdan mesafe - sertifika tipine göre ayarla
        if ($certificateType === 'ders') {
            $rightMargin = 38; // Ders tipi için sağdan 38mm mesafe
            $contentRightMargin = 42; // İçerik yazıları için biraz daha fazla mesafe (45 -> 42, biraz sola)
        } else {
            $rightMargin = 30;
            $contentRightMargin = 34; // İçerik yazıları için biraz daha fazla mesafe (37 -> 34, biraz sola)
        }
        $registerX = $size['width'] - $rightMargin;
        $contentX = $size['width'] - $contentRightMargin; // İçerik yazıları için ayrı X pozisyonu
        $cellWidth = 70;
        
        
        // Register No yazısı - Türkçe karakter desteği için
        $pdf->SetXY($registerX - $cellWidth, $registerY);
        $registerText = 'Register No: ' . $registerNo;
        // Cell() metodunu kullan ama font'un Türkçe karakter desteği olduğundan emin ol
        $pdf->Cell($cellWidth, 10, $registerText, 0, 0, 'R', false, '', 0, false, 'T', 'C');
        
        // İçerik 1 yazısı (Register No'nun altında) - Biraz daha sağa mesafe
        $pdf->SetFont('dejavuserif', 'I', 8); // Küçük italik font (10 -> 8, italik)
        $pdf->SetXY($contentX - $cellWidth, $content1Y);
        $pdf->Cell($cellWidth, 10, $content1, 0, 0, 'R');
        
        // İçerik 2 yazısı (İçerik 1'in altında) - Biraz daha sağa mesafe
        if (!empty($content2)) {
            $pdf->SetFont('dejavuserif', 'I', 8); // Küçük italik font
            $pdf->SetXY($contentX - $cellWidth, $content2Y);
            $pdf->Cell($cellWidth, 10, $content2, 0, 0, 'R');
        }
        
        // Tarih yazısı kaldırıldı
        
        $pdf->SetTextColor(0, 0, 0); // Siyah renk
        
        // Ad Soyad için font boyutu - Sertifika tipine göre (küçültüldü)
        $fontSize = ($certificateType === 'ders') ? 39 : 40; // Ders: 45->38->39, Kurs: 40->34->36->37->38->39->40 (bir tık daha büyütüldü)
        
        $x = 0;
        
        if ($certificateType === 'kurs') {
            $cellHeight = 10;
            $bottomMargin = 118; // 117 -> 118 (çok az daha yukarıya taşımak için artırıldı)
            if (isset($size['height']) && $size['height'] > 0) {
                $y = $size['height'] - $cellHeight - $bottomMargin;
            } else {
                $y = 150; // 151 -> 150 (çok az daha yukarıya taşımak için azaltıldı)
            }
        } else {
            if (isset($size['height']) && $size['height'] > 0) {
                $y = $size['height'] * 0.46; // 0.48 -> 0.46 (daha yukarı)
            } else {
                $y = 138; // 143 -> 138 (daha yukarı)
            }
        }
        
        // Ad Soyad yazısı - "ğ" karakteri için MiddleAges_PERSONAL_USE, diğerleri için CloisterBlack
        $pdf->SetTextColor(0, 0, 0);
        
        // Metinde "ğ" karakteri var mı kontrol et
        $hasG = (mb_strpos($userName, 'ğ') !== false || mb_strpos($userName, 'Ğ') !== false);
        
        // Font seçimi: "ğ" varsa MiddleAges_PERSONAL_USE, yoksa CloisterBlack
        if ($hasG && $middleAgesFontName) {
            $nameFontName = $middleAgesFontName;
        } else {
            $nameFontName = $cloisterFontName ? $cloisterFontName : $turkishFallbackFont;
        }
        
        $pdf->SetFont($nameFontName, '', $fontSize);
        
        // Metni ortalamak için Write() kullan - Unicode karakterleri daha iyi destekler
        // Önce metnin genişliğini hesapla
        $textWidth = $pdf->GetStringWidth($userName);
        $startX = ($size['width'] - $textWidth) / 2;
        $pdf->SetXY($startX, $y);
        $pdf->Write(10, $userName, '', 0, '', false, 0, false, false, 0);
        
        $pdf->SetCreator('Kariyer Sistemi');
        $pdf->SetAuthor('Kariyer Sistemi');
        $pdf->SetTitle($certificate->certificate_name ?? 'Sertifika');
        $pdf->SetSubject('Sertifika');
        
        // PDF şifre koruması - Her iki sertifika tipi (ders ve kurs) için de uygulanır
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

}