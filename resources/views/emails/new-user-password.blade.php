<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hesap Bilgileriniz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #1173d4;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .password-box {
            background-color: #e8f4fd;
            border: 2px solid #1173d4;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin: 20px 0;
        }
        .password-text {
            font-size: 18px;
            font-weight: bold;
            color: #1173d4;
            font-family: monospace;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ASI Kariyer Hesabınız Oluşturuldu</h1>
    </div>
    
    <div class="content">
        <h2>Merhaba {{ $user->name }},</h2>
        
        <p>ASI Kariyer platformuna hoş geldiniz! Hesabınız başarıyla oluşturuldu.</p>
        
        <p><strong>Giriş bilgileriniz:</strong></p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        
        <div class="password-box">
            <p><strong>Geçici Şifreniz:</strong></p>
            <div class="password-text">{{ $password }}</div>
        </div>
        
        <p>Güvenliğiniz için lütfen ilk girişinizde şifrenizi değiştirin.</p>
        
        <p>Giriş yapmak için: <a href="{{ url('/login') }}" style="color: #1173d4;">Buraya tıklayın</a></p>
        
        <p>Bu şifre sadece ilk girişiniz için geçerlidir. Lütfen güvenliğiniz için şifrenizi değiştirin.</p>
    </div>
    
    <div class="footer">
        <p>Bu email otomatik olarak gönderilmiştir. Lütfen yanıtlamayın.</p>
    </div>
</body>
</html>