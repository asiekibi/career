# Windows'ta PHP Dosya Yükleme Limit Ayarları

40MB dosya yüklemek için Windows'ta PHP ayarlarını yapmanız gerekiyor:

## 1. PHP.ini Dosyasını Bulma

PHP.ini dosyasının konumunu bulmak için:

**Yöntem 1: Komut satırından**
```bash
php --ini
```

**Yöntem 2: PHP Info ile**
Bir PHP dosyası oluşturun (`public/phpinfo.php`):
```php
<?php
phpinfo();
```

Tarayıcıda açın ve "Loaded Configuration File" satırını bulun.

## 2. PHP.ini Dosyasını Düzenleme

Bulduğunuz `php.ini` dosyasını bir metin editörü ile açın (Notepad++ veya VS Code) ve şu satırları bulun:

```ini
upload_max_filesize = 2M
post_max_size = 8M
max_execution_time = 30
max_input_time = 60
memory_limit = 128M
```

Bu değerleri şu şekilde değiştirin:

```ini
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
max_input_time = 300
memory_limit = 256M
```

**Önemli:** `post_max_size` değeri `upload_max_filesize` değerinden **eşit veya büyük** olmalıdır!

## 3. Web Sunucusunu Yeniden Başlatma

### XAMPP Kullanıyorsanız:
- XAMPP Control Panel'den Apache'yi durdurun ve tekrar başlatın

### Laravel Artisan Serve Kullanıyorsanız:
- Terminal'de `Ctrl+C` ile durdurun
- `php artisan serve` ile tekrar başlatın

### PHP-FPM veya diğer sunucular:
- İlgili servisi yeniden başlatın

## 4. Ayarları Kontrol Etme

Ayarların doğru yapıldığını kontrol etmek için:

```bash
php -i | findstr "upload_max_filesize post_max_size"
```

Veya `public/phpinfo.php` dosyasını tarayıcıda açarak kontrol edin.

## 5. Laravel Validation

Laravel tarafında validation limiti zaten 40MB (40960 KB) olarak ayarlandı:
- `app/Http/Controllers/CertificateController.php` - `store()` ve `update()` metodlarında

## Sorun Devam Ederse

1. PHP.ini dosyasını düzenledikten sonra web sunucusunu mutlaka yeniden başlatın
2. Tarayıcı cache'ini temizleyin
3. `phpinfo()` ile ayarların gerçekten değiştiğini kontrol edin
4. Eğer birden fazla PHP kurulumu varsa, doğru `php.ini` dosyasını düzenlediğinizden emin olun
