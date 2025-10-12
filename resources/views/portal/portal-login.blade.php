<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Sertifika Doğrulama</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&amp;display=swap" rel="stylesheet"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#11d411",
                        "background-light": "#f6f8f6",
                        "background-dark": "#102210",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<!-- main content -->
<body class="bg-background-light dark:bg-background-dark font-display text-gray-900 dark:text-gray-100">
    <div class="flex flex-col min-h-screen">
        <header class="border-b border-gray-200 dark:border-gray-800">
            <!-- header -->
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center gap-4">
                        <div class="text-primary">
                            <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                <path d="M44 4H30.6666V17.3334H17.3334V30.6666H4V44H44V4Z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold">SertifikaSorgula</h2>
                    </div>
                </div>
            </div>
        </header>

        <!-- main content -->
        <main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-md space-y-8">
                <div class="text-center">
                    <h1 class="text-4xl font-extrabold tracking-tight">Sertifikanızı Doğrulayın</h1>
                    <p class="mt-4 text-lg text-gray-500 dark:text-gray-400">CV'nizi görüntülemek için sertifika kodunuzu girin.</p>
                </div>
                
                <form id="certificateForm" class="space-y-6">
                    <div class="relative">
                        <input id="certificateCode" class="w-full h-14 pl-4 pr-12 bg-background-light dark:bg-background-dark border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-primary focus:border-primary" placeholder="Sertifika Kodunu Girin" type="text" required/>
                    </div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-black bg-primary hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        Sorgula
                    </button>
                </form>
                
                <!-- Hata mesajı -->
                <div id="errorMessage" class="text-center space-y-6 pt-6 hidden">
                    <div class="flex justify-center">
                        <svg class="h-16 w-16 text-red-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <div class="space-y-2">
                        <p class="text-xl font-bold">Sertifika kaydına ulaşılamamıştır.</p>
                        <p class="text-gray-500 dark:text-gray-400">Lütfen sertifika kodunu kontrol edip tekrar deneyin.</p>
                    </div>
                </div>
                
                <!-- Başarılı sonuç -->
                <div id="successResult" class="pt-6 hidden">
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden">
                        <div class="relative h-48 w-full bg-cover bg-center" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuD5P26mZNldhffbC9SQb803JfXreNDhcj168GOtC_C4vt1wSUR96bzYR5MQj5kQd3gL_zdJUo8OizY1IFb9mIwQljWxDgvgAFti32RrH2J3AWtpoAuYGl48giyynttJM-GQS0OXGgL9S1S27sWT4o23-DpFgudSsJjZ-wn-wHD5T8oKywJ6NoLqkX30GSdCI3UIRYk3kU879sov8gAtuVGMEnn1QMs8O7UXoYy8MlNtqCgt2sGF7evnpOuGplvUT6ePBcDbQ_plP3g");'>
                            <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                            <div class="absolute bottom-4 left-4">
                                <img id="studentPhoto" alt="Profil Fotoğrafı" class="h-24 w-24 rounded-full border-4 border-white dark:border-gray-800" src=""/>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 id="studentName" class="text-2xl font-bold"></h3>
                            <p class="text-primary font-semibold">Öğrenci</p>
                            <p class="mt-4 text-gray-600 dark:text-gray-300">
                                Sertifika başarıyla doğrulandı. Öğrencinin bilgilerini aşağıda görebilirsiniz.
                            </p>
                            <div class="mt-6">
                                <button id="viewCvBtn" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-black bg-primary hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                    Özgeçmişini Gör
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // certificate form submit
        document.getElementById('certificateForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const certificateCode = document.getElementById('certificateCode').value;
            const errorMessage = document.getElementById('errorMessage');
            const successResult = document.getElementById('successResult');
            
            // Loading göster
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Sorgulanıyor...';
            submitBtn.disabled = true;
            
            fetch('{{ route("portal.search") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    certificate_code: certificateCode
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // success result show
                    document.getElementById('studentName').textContent = data.student.name + ' ' + data.student.surname;
                    document.getElementById('studentPhoto').src = data.student.profile_photo_url || 'https://via.placeholder.com/96';
                    document.getElementById('viewCvBtn').onclick = function() {
                        window.location.href = '{{ url("portal/student-cv") }}/' + data.student.id;
                    };
                    
                    errorMessage.classList.add('hidden');
                    successResult.classList.remove('hidden');
                } else {
                    // error message show
                    successResult.classList.add('hidden');
                    errorMessage.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                successResult.classList.add('hidden');
                errorMessage.classList.remove('hidden');
            })
            .finally(() => {
                // Loading remove
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    </script>
</body>
</html>