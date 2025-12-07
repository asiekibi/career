# DigitalOcean Droplet Laravel Deployment Rehberi

## 1. SSH ile Sunucuya Bağlanma

```bash
ssh root@142.93.233.17
```

Veya eğer SSH key kullanıyorsanız:
```bash
ssh -i ~/.ssh/your_key root@142.93.233.17
```

## 2. Sistem Güncellemesi

```bash
apt update
apt upgrade -y
```

## 3. Gerekli Paketleri Kurma

### PHP ve Extension'ları
```bash
apt install -y php8.2-fpm php8.2-cli php8.2-common php8.2-mysql php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath
```

### Nginx Web Sunucusu
```bash
apt install -y nginx
```

### MySQL Veritabanı
```bash
apt install -y mysql-server
```

### Composer
```bash
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
```

### Git
```bash
apt install -y git
```

## 4. MySQL Kurulumu ve Veritabanı Oluşturma

```bash
mysql_secure_installation
```

Sonra MySQL'e giriş yap:
```bash
mysql -u root -p
```

Veritabanı oluştur:
```sql
CREATE DATABASE kariyer CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'kariyer_user'@'localhost' IDENTIFIED BY 'güçlü_şifre_buraya';
GRANT ALL PRIVILEGES ON kariyer.* TO 'kariyer_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

## 5. Projeyi Sunucuya Yükleme

### Seçenek 1: Git ile (Önerilen)
```bash
cd /var/www
git clone https://github.com/kullanici/kariyer.git
# veya
git clone https://github.com/kullanici/kariyer.git kariyer
cd kariyer
```

### Seçenek 2: SFTP ile
- FileZilla veya WinSCP kullanarak projeyi `/var/www/kariyer` klasörüne yükleyin

## 6. Proje İzinlerini Ayarlama

```bash
cd /var/www/kariyer
chown -R www-data:www-data /var/www/kariyer
chmod -R 755 /var/www/kariyer
chmod -R 775 storage bootstrap/cache
```

## 7. .env Dosyasını Yapılandırma

```bash
cp .env.example .env
nano .env
```

`.env` dosyasında şunları güncelle:
```env
APP_NAME=Kariyer
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://asiaccreditation.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kariyer
DB_USERNAME=kariyer_user
DB_PASSWORD=güçlü_şifre_buraya
```

## 8. Composer ve NPM Bağımlılıklarını Yükleme

```bash
composer install --optimize-autoloader --no-dev
```

Eğer frontend build gerekiyorsa:
```bash
npm install
npm run build
```

## 9. Application Key Oluşturma

```bash
php artisan key:generate
```

## 10. Migration ve Storage Link

```bash
php artisan migrate --force
php artisan storage:link
```

## 11. Nginx Yapılandırması

```bash
nano /etc/nginx/sites-available/kariyer
```

Aşağıdaki yapılandırmayı ekle:
```nginx
server {
    listen 80;
    server_name asiaccreditation.com www.asiaccreditation.com 142.93.233.17;
    root /var/www/kariyer/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Symlink oluştur:
```bash
ln -s /etc/nginx/sites-available/kariyer /etc/nginx/sites-enabled/
rm /etc/nginx/sites-enabled/default
```

Nginx yapılandırmasını test et:
```bash
nginx -t
```

## 12. Servisleri Başlatma

```bash
systemctl start nginx
systemctl start php8.2-fpm
systemctl start mysql
systemctl enable nginx
systemctl enable php8.2-fpm
systemctl enable mysql
```

## 13. Firewall Ayarları

```bash
ufw allow 22/tcp
ufw allow 80/tcp
ufw allow 443/tcp
ufw enable
```

## 14. SSL Sertifikası Kurulumu (Let's Encrypt)

### Domain Bilgileri:
- **IP Adresi:** 142.93.233.17
- **Domain:** asiaccreditation.com
- **Tam URL:** https://asiaccreditation.com

### Ön Hazırlık:
Domain'in IP adresine yönlendirildiğinden emin olun. DNS kayıtlarınızda A kaydı şu şekilde olmalı:
```
A    @    142.93.233.17
A    www  142.93.233.17
```

### SSL Sertifikası Kurulumu:

1. **Certbot ve Nginx eklentisini kurun:**
```bash
apt install -y certbot python3-certbot-nginx
```

2. **Nginx yapılandırmasını domain için güncelleyin:**
```bash
nano /etc/nginx/sites-available/kariyer
```

Nginx yapılandırmasında `server_name` satırını güncelleyin:
```nginx
server {
    listen 80;
    server_name asiaccreditation.com www.asiaccreditation.com;
    root /var/www/kariyer/public;
    # ... diğer ayarlar ...
}
```

3. **Nginx yapılandırmasını test edin ve yeniden yükleyin:**
```bash
nginx -t
systemctl reload nginx
```

4. **SSL sertifikasını alın:**
```bash
certbot --nginx -d asiaccreditation.com -d www.asiaccreditation.com
```

Certbot size birkaç soru soracak:
- Email adresi girin (sertifika yenileme bildirimleri için)
- Hizmet şartlarını kabul edin (A)
- HTTP trafiğini HTTPS'e yönlendirmek isteyip istemediğinizi soracak (2 seçin - otomatik yönlendirme için)

5. **Sertifika otomatik yenileme testi:**
```bash
certbot renew --dry-run
```

6. **.env dosyasını HTTPS için güncelleyin:**
```bash
nano /var/www/kariyer/.env
```

`APP_URL` satırını güncelleyin:
```env
APP_URL=https://asiaccreditation.com
```

7. **Laravel cache'lerini temizleyin:**
```bash
cd /var/www/kariyer
php artisan config:clear
php artisan config:cache
```

### SSL Sertifikası Otomatik Yenileme:
Let's Encrypt sertifikaları 90 günde bir yenilenir. Certbot otomatik olarak bunu yönetir, ancak manuel kontrol için:
```bash
certbot renew
```

Sertifika yenileme durumunu kontrol etmek için:
```bash
certbot certificates
```

## 15. Optimizasyon

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Sorun Giderme

### Nginx durumunu kontrol et:
```bash
systemctl status nginx
```

### PHP-FPM durumunu kontrol et:
```bash
systemctl status php8.2-fpm
```

### Log dosyalarını kontrol et:
```bash
tail -f /var/log/nginx/error.log
tail -f /var/www/kariyer/storage/logs/laravel.log
```

### Port kontrolü:
```bash
netstat -tulpn | grep :80
```

## Hızlı Komutlar

```bash
# Servisleri yeniden başlat
systemctl restart nginx
systemctl restart php8.2-fpm

# Cache temizle
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Logları temizle
> /var/www/kariyer/storage/logs/laravel.log
```


