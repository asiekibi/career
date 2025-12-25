<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmailValidator
{
    /**
     * Email adresinin gerçekten var olup olmadığını kontrol et
     * 
     * @param string $email
     * @return array ['valid' => bool, 'message' => string, 'details' => array]
     */
    public static function validateEmail($email)
    {
        // Email format kontrolü
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'valid' => false,
                'message' => 'Email formatı geçersiz',
                'details' => []
            ];
        }

        // Domain ve MX kayıt kontrolü
        $emailParts = explode('@', $email);
        if (count($emailParts) !== 2) {
            return [
                'valid' => false,
                'message' => 'Email formatı geçersiz',
                'details' => []
            ];
        }

        $domain = $emailParts[1];

        // MX kayıt kontrolü
        if (!checkdnsrr($domain, 'MX')) {
            return [
                'valid' => false,
                'message' => 'Domain geçersiz veya MX kaydı yok',
                'details' => ['domain' => $domain]
            ];
        }

        // SMTP sunucusuna RCPT TO komutu göndererek mailbox kontrolü
        // NOT: Bu yöntem her zaman çalışmayabilir çünkü bazı SMTP sunucuları
        // mailbox kontrolü yapmadan maili kabul eder
        $mxRecords = [];
        getmxrr($domain, $mxRecords);

        if (empty($mxRecords)) {
            return [
                'valid' => false,
                'message' => 'MX kaydı bulunamadı',
                'details' => ['domain' => $domain]
            ];
        }

        // İlk MX kaydını kullan
        $mxHost = $mxRecords[0];
        $mxPort = 25;

        // SMTP bağlantısı kur ve RCPT TO kontrolü yap
        try {
            $connection = @fsockopen($mxHost, $mxPort, $errno, $errstr, 5);
            
            if (!$connection) {
                // Bağlantı kurulamadı, ama bu mailbox'ın olmadığı anlamına gelmez
                // Bazı SMTP sunucuları dış bağlantıları kabul etmez
                return [
                    'valid' => true, // Bağlantı kurulamadı ama format geçerli
                    'message' => 'SMTP sunucusuna bağlanılamadı, ancak format geçerli',
                    'details' => [
                        'domain' => $domain,
                        'mx_host' => $mxHost,
                        'note' => 'SMTP sunucusu dış bağlantıları kabul etmiyor olabilir. Mail gönderimi denenebilir.'
                    ]
                ];
            }

            // SMTP handshake
            $response = fgets($connection, 515);
            if (substr($response, 0, 3) !== '220') {
                fclose($connection);
                return [
                    'valid' => true, // SMTP yanıtı beklenmedik ama format geçerli
                    'message' => 'SMTP sunucusu beklenmedik yanıt verdi',
                    'details' => ['response' => $response]
                ];
            }

            // HELO komutu
            fputs($connection, "HELO " . (env('APP_URL') ? parse_url(env('APP_URL'), PHP_URL_HOST) : 'localhost') . "\r\n");
            $response = fgets($connection, 515);

            // MAIL FROM komutu
            fputs($connection, "MAIL FROM: <" . env('MAIL_FROM_ADDRESS', 'noreply@example.com') . ">\r\n");
            $response = fgets($connection, 515);

            // RCPT TO komutu - mailbox kontrolü
            fputs($connection, "RCPT TO: <" . $email . ">\r\n");
            $response = fgets($connection, 515);
            $responseCode = substr($response, 0, 3);

            // QUIT komutu
            fputs($connection, "QUIT\r\n");
            fclose($connection);

            // 250 = Mailbox var, 550 = Mailbox yok
            if ($responseCode === '250') {
                return [
                    'valid' => true,
                    'message' => 'Mailbox mevcut',
                    'details' => [
                        'domain' => $domain,
                        'mx_host' => $mxHost,
                        'smtp_response' => trim($response),
                        'smtp_code' => $responseCode
                    ]
                ];
            } elseif ($responseCode === '550') {
                return [
                    'valid' => false,
                    'message' => 'Mailbox bulunamadı',
                    'details' => [
                        'domain' => $domain,
                        'mx_host' => $mxHost,
                        'smtp_response' => trim($response),
                        'smtp_code' => $responseCode
                    ]
                ];
            } else {
                // Beklenmedik yanıt
                return [
                    'valid' => true, // Belirsiz, mail gönderimi denenebilir
                    'message' => 'SMTP sunucusu beklenmedik yanıt verdi',
                    'details' => [
                        'domain' => $domain,
                        'mx_host' => $mxHost,
                        'smtp_response' => trim($response),
                        'smtp_code' => $responseCode
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Hata durumunda format geçerli kabul et
            return [
                'valid' => true,
                'message' => 'SMTP kontrolü yapılamadı, ancak format geçerli',
                'details' => [
                    'domain' => $domain,
                    'error' => $e->getMessage()
                ]
            ];
        }
    }
}
