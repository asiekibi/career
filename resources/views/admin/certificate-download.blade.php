<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Sertifika - {{ $userCertificate->certificate->certificate_name ?? 'Sertifika' }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                margin: 0;
                padding: 20px;
            }
            .certificate-container {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body class="bg-gray-50 font-display">
    <div class="min-h-screen py-8 px-4">
        <!-- Print Button -->
        <div class="max-w-4xl mx-auto mb-6 no-print">
            <button onclick="window.print()" 
                    class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                <span class="material-symbols-outlined">print</span>
                Yazdır / PDF Olarak Kaydet
            </button>
        </div>

        <!-- Certificate Container -->
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8 md:p-12 certificate-container">
            <!-- Certificate Header -->
            <div class="text-center mb-8 border-b-2 border-gray-300 pb-6">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-2">SERTİFİKA</h1>
                <p class="text-lg text-gray-600">Bu belge, aşağıda belirtilen kişinin belirtilen sertifikaya sahip olduğunu doğrular.</p>
            </div>

            <!-- Certificate Content -->
            <div class="space-y-6 mb-8">
                <!-- Certificate Name -->
                <div class="text-center">
                    <h2 class="text-3xl md:text-4xl font-semibold text-primary mb-4">
                        {{ $userCertificate->certificate->certificate_name ?? 'Sertifika' }}
                    </h2>
                </div>

                <!-- User Information -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Sertifika Sahibi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Ad Soyad</p>
                            <p class="text-lg font-medium text-gray-800">
                                {{ $userCertificate->user->name }} {{ $userCertificate->user->surname }}
                            </p>
                        </div>
                        @if($userCertificate->certificate_code)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Sertifika Kodu</p>
                            <p class="text-lg font-medium text-gray-800">{{ $userCertificate->certificate_code }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Certificate Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($userCertificate->issuing_institution)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Veren Kurum</p>
                        <p class="text-lg font-medium text-gray-800">{{ $userCertificate->issuing_institution }}</p>
                    </div>
                    @endif

                    @if($userCertificate->acquisition_date)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Veriliş Tarihi</p>
                        <p class="text-lg font-medium text-gray-800">
                            {{ \Carbon\Carbon::parse($userCertificate->acquisition_date)->format('d.m.Y') }}
                        </p>
                    </div>
                    @endif

                    @if($userCertificate->achievement_score)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Başarı Puanı</p>
                        <p class="text-lg font-medium text-gray-800">{{ $userCertificate->achievement_score }}</p>
                    </div>
                    @endif

                    @if($userCertificate->acquisition_date && $userCertificate->validity_period)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Geçerlilik Sonu</p>
                        <p class="text-lg font-medium text-gray-800">
                            @php
                                $validityPeriod = is_numeric($userCertificate->validity_period) ? (int)$userCertificate->validity_period : 0;
                            @endphp
                            {{ \Carbon\Carbon::parse($userCertificate->acquisition_date)->addYears($validityPeriod)->format('d.m.Y') }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Certificate Footer -->
            <div class="mt-12 pt-6 border-t-2 border-gray-300">
                <div class="text-center text-sm text-gray-600">
                    <p>Bu sertifika dijital olarak doğrulanabilir.</p>
                    <p class="mt-2">Sertifika Kodu: <strong>{{ $userCertificate->certificate_code ?? 'Belirtilmemiş' }}</strong></p>
                    <p class="mt-4 text-xs text-gray-500">
                        Oluşturulma Tarihi: {{ now()->format('d.m.Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

