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
APP_URL=http://142.93.233.17

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
    server_name 142.93.233.17;
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

## 14. SSL Sertifikası (Opsiyonel - Let's Encrypt)

```bash
apt install -y certbot python3-certbot-nginx
certbot --nginx -d yourdomain.com
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

