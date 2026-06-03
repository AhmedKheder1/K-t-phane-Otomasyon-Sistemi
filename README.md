# 📚 Kütüphane Otomasyon Sistemi

Bartın Belediyesi Halk Kütüphanesi için geliştirilmiş web tabanlı otomasyon sistemi.

---

## 🗂️ Proje Yapısı

```
├── index.php              → Ana Sayfa (Landing Page)
├── admin_index.php        → Yönetici Paneli
├── admin_login.php        → Yönetici Giriş Sayfası
├── user_index.php         → Üye Paneli
├── user_login.php         → Üye Giriş Sayfası
├── api.php                → API (AJAX istekleri)
├── business.php           → Business Layer (İş Kuralları)
├── database.php           → Data Access Layer (Veritabanı)
└── kutuphane_v2.sql       → Veritabanı (Tablolar + SP + Trigger + Function)
```

---

## ⚙️ Kurulum

**1.** XAMPP kurun → Apache ve MySQL başlatın

**2.** Bu dosyaları `C:\xampp\htdocs\kutuphane\` klasörüne kopyalayın

**3.** `phpMyAdmin` açın → `kutuphane_v2.sql` dosyasını import edin

**4.** Tarayıcıdan açın:
```
http://localhost/kutuphane/
```

---

## 👤 Demo Hesaplar

| Rol | Kullanıcı Adı | Şifre |
|-----|--------------|-------|
| Yönetici | admin | admin123 |
| Üye | ahmet | user123 |
| Üye | fatma | user123 |

---

## ✅ Özellikler

- 📖 Kitap kataloğu ve stok takibi
- 👥 Üye yönetimi (rol bazlı: Admin / Üye)
- 🔄 Ödünç verme ve iade işlemleri
- ✉️ Ödünç talep sistemi
- ⏱️ Gecikme takibi
- 💰 Otomatik ceza hesaplama
- 🏷️ Kategori yönetimi

---

## 🗄️ Veritabanı

| Bileşen | Sayı |
|---------|------|
| Tablolar | 6 |
| Stored Procedures | 28 |
| Functions | 2 |
| Triggers | 2 |

---

## 🛠️ Teknolojiler

- **Backend:** PHP (N-Katmanlı Mimari)
- **Veritabanı:** MySQL
- **Frontend:** HTML, CSS, JavaScript
- **Sunucu:** XAMPP / Apache
