# Kariyer Sistemi

Laravel 12 tabanlı modern kariyer yönetim sistemi. Öğrencilerin CV'lerini oluşturmasına, sertifika ve rozet kazanmasına olanak tanıyan kapsamlı bir platform.

## 🚀 Özellikler

### 👥 Kullanıcı Yönetimi
- **Admin Paneli**: Kullanıcıları yönetme, CV'leri görüntüleme
- **Öğrenci Paneli**: CV oluşturma ve düzenleme
- **Portal**: Sertifika arama ve öğrenci CV'lerini görüntüleme
- **Rol Tabanlı Erişim**: Admin ve kullanıcı rolleri
- **Profil Fotoğrafı**: Kullanıcı profil fotoğraflarını yükleme

### 📄 CV Yönetimi
- **Kişisel Bilgiler**: Ad, soyad, doğum tarihi, cinsiyet, iletişim bilgileri
- **Deneyim**: İş deneyimlerini ekleme, düzenleme, silme
- **Eğitim**: Eğitim geçmişini yönetme
- **Yetenekler**: Teknik ve kişisel yetenekleri listeleme
- **Diller**: Dil bilgilerini kaydetme
- **Hobiler**: Kişisel hobileri ekleme
- **Özet**: CV özeti yazma

### 🏆 Rozet ve Sertifika Sistemi
- **Rozetler**: Puan tabanlı rozet sistemi
- **Sertifikalar**: Eğitim sertifikalarını yönetme
- **Puan Sistemi**: Kullanıcı puanları ve rozet kazanma
- **Admin Atama**: Adminlerin kullanıcılara rozet ve sertifika ataması

### 🌍 Konum Yönetimi
- **Şehir/İlçe**: Türkiye'deki şehir ve ilçe bilgileri
- **Dinamik Seçim**: Şehir seçimine göre ilçe listesi

### 🔐 Güvenlik
- **Kimlik Doğrulama**: Laravel'in yerleşik auth sistemi
- **Şifre Sıfırlama**: Email ile şifre sıfırlama
- **Middleware**: Rol tabanlı erişim kontrolü
- **Soft Delete**: Güvenli veri silme

## 🛠️ Teknolojiler

- **Backend**: Laravel 12, PHP 8.2+
- **Frontend**: Blade Templates, TailwindCSS 4.0
- **Veritabanı**: SQLite (geliştirme), MySQL/PostgreSQL (üretim)
- **Build Tool**: Vite 7.0
- **Testing**: PHPUnit

## 📋 Gereksinimler

- PHP 8.2 veya üzeri
- Composer

## 🚀 Kurulum

### 1. Projeyi Klonlayın
```bash
git clone <repository-url>
cd kariyer
```

### 2. Bağımlılıkları Yükleyin
```bash
composer install
npm install
```

### 3. Ortam Değişkenlerini Ayarlayın
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Veritabanını Hazırlayın
```bash
# SQLite veritabanı oluştur
touch database/database.sqlite

# Migration'ları çalıştır
php artisan migrate

# Seed verilerini yükle
php artisan db:seed
```


### 5. Sunucuyu Başlatın
```bash
php artisan serve
```

