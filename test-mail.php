<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;

try {
    Mail::raw('Bu bir test mailidir. Mail sistemi çalışıyor!', function($message) {
        $message->to('test@example.com')
                ->subject('Test Mail - ASI Kariyer');
    });
    
    echo "Mail başarıyla gönderildi!\n";
} catch (\Exception $e) {
    echo "Hata: " . $e->getMessage() . "\n";
}








