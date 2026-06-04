<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$certs = \App\Models\UserCertificate::whereIn('id', [1, 3, 5])->get();
foreach ($certs as $cert) {
    echo "ID: {$cert->id}\n";
    echo "Content1: " . var_export($cert->content1, true) . "\n";
    echo "Content2: " . var_export($cert->content2, true) . "\n";
    echo "---------------------------\n";
}
