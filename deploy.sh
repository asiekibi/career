#!/bin/bash

# DigitalOcean Laravel Deployment Script
# Bu script'i sunucuda çalıştırın

echo "=== Sistem Güncellemesi ==="
apt update && apt upgrade -y

echo "=== Gerekli Paketlerin Kurulumu ==="
apt install -y nginx mysql-server php8.2-fpm php8.2-cli php8.2-common php8.2-mysql php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath git unzip

echo "=== Composer Kurulumu ==="
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

echo "=== MySQL Güvenlik Kurulumu ==="
echo "MySQL root şifresini ayarlayın:"
mysql_secure_installation

echo "=== Veritabanı Oluşturma ==="
echo "MySQL'e giriş yapıp veritabanı oluşturun:"
echo "mysql -u root -p"
echo ""
echo "Sonra şu komutları çalıştırın:"
echo "CREATE DATABASE kariyer CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
echo "CREATE USER 'kariyer_user'@'localhost' IDENTIFIED BY 'güçlü_şifre';"
echo "GRANT ALL PRIVILEGES ON kariyer.* TO 'kariyer_user'@'localhost';"
echo "FLUSH PRIVILEGES;"
echo "EXIT;"

echo ""
echo "=== Kurulum Tamamlandı ==="
echo "Şimdi projeyi /var/www/kariyer klasörüne yükleyin"








