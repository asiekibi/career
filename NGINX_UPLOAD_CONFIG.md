# Nginx ve PHP Dosya Yükleme Limit Ayarları

40MB dosya yüklemek için sunucuda şu ayarları yapmanız gerekiyor:

## 1. Nginx Ayarları

Nginx yapılandırma dosyanızı düzenleyin (genellikle `/etc/nginx/sites-available/default` veya `/etc/nginx/sites-available/kariyer`):

```nginx
server {
    # ... diğer ayarlar ...
    
    # Dosya yükleme limitini artır (50MB)
    client_max_body_size 50M;
    
    # ... diğer ayarlar ...
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param PHP_VALUE "upload_max_filesize=50M \n post_max_size=50M";
        # ... diğer fastcgi ayarları ...
    }
}
```

Nginx'i yeniden yükleyin:
```bash
sudo nginx -t  # Yapılandırmayı test et
sudo systemctl reload nginx
```

## 2. PHP-FPM Ayarları

PHP-FPM yapılandırma dosyasını düzenleyin (`/etc/php/8.2/fpm/php.ini`):

```ini
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
max_input_time = 300
memory_limit = 256M
```

PHP-FPM'i yeniden başlatın:
```bash
sudo systemctl restart php8.2-fpm
```

## 3. Kontrol

PHP ayarlarını kontrol etmek için:
```bash
php -i | grep -E "upload_max_filesize|post_max_size"
```

Veya bir PHP dosyası oluşturun:
```php
<?php
phpinfo();
```

Bu dosyayı tarayıcıda açarak `upload_max_filesize` ve `post_max_size` değerlerini kontrol edin.
