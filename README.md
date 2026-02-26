README FINAL — BACKEND → FRONTEND HANDOVER
# 🖥️ Sistem Pengaduan IT BPS Kota Palangka Raya

Sistem Pengaduan IT berbasis **Laravel 12** untuk pengelolaan laporan kerusakan Barang Milik Negara (BMN) di lingkungan internal instansi.

Project ini menggunakan **workflow approval berlapis**:

Pegawai → Admin IT → Kasubag → Status Final Barang

---

## 👨‍💻 Tujuan Repository

Repository ini merupakan:

✅ Backend utama sistem  
✅ Workflow & Business Logic Authority  
✅ Database Structure Source  
✅ API + Controller Logic  
✅ Security & Audit System  

Frontend Team **HANYA fokus UI/UX** tanpa mengubah logic backend.

---

# ⚙️ Tech Stack

- Laravel 12
- PHP 8.2+
- MySQL
- Tailwind CSS
- Vite
- Laravel Middleware Security
- Role Based Access Control
- Audit Logging System

---

# 🧱 Role Sistem

| Role | Akses |
|------|------|
| Pegawai | Membuat laporan |
| Admin IT | Verifikasi & rekomendasi |
| Kasubag | Keputusan final |
| System | Audit & Workflow Control |

---

# 🔄 Workflow Sistem


Pegawai
↓
MENUNGGU_REVIEW_ADMIN
↓
Admin Verifikasi
↓
MENUNGGU_KEPUTUSAN_KASUBAG
↓
Kasubag Decision
↓
SELESAI / DITOLAK


⚠️ Status dikontrol backend dan **tidak boleh diubah dari frontend**.

---

# 🚀 Setup Project (WAJIB DIKUTI)

## 1️⃣ Clone Repository

```bash
git clone https://github.com/mrcatfish75-debug/pengaduan-it-bps.git
cd pengaduan-it-bps
2️⃣ Install Dependency
composer install
npm install
3️⃣ Environment Setup

Copy file environment:

cp .env.example .env

Generate key:

php artisan key:generate
4️⃣ Database Setup

Buat database baru:

pengaduan_it_bps

Edit .env:

DB_DATABASE=pengaduan_it_bps
DB_USERNAME=root
DB_PASSWORD=
⚠️ PENTING — DATABASE RULE

Gunakan:

php artisan migrate
php artisan db:seed

❌ DILARANG

php artisan migrate:fresh

Karena akan menghapus:

Master Barang (BMN)

Workflow data

Foreign Key relation

5️⃣ Jalankan Project
npm run dev
php artisan serve

Akses:

http://127.0.0.1:8000
🔐 Akun Default (Seeder)
Role	Email	Password
Admin	admin@bps.go.id
	password
Kasubag	kasubag@bps.go.id
	password
Pegawai	pegawai@bps.go.id
	password
📁 Struktur Penting Project
app/
 ├── Http/
 │    ├── Controllers
 │    ├── Middleware
 │    └── Requests
 ├── Models
 ├── Support (Workflow Engine)
 └── Services

resources/
 └── views ← FRONTEND AREA
🎨 AREA FRONTEND (BOLEH DIUBAH)

Frontend Team boleh mengubah:

resources/views/*
resources/css/*
resources/js/*

✅ UI
✅ Layout
✅ Styling
✅ UX Improvement

🚫 AREA BACKEND (JANGAN DIUBAH)

Tanpa koordinasi Backend Owner:

app/Models
app/Http/Controllers
app/Support
database/
routes/
config/security.php

Mengubah bagian ini dapat:

❌ merusak workflow
❌ merusak keamanan
❌ menyebabkan data corruption

🧾 Activity Log System

Semua aksi tercatat otomatis:

Login

Create laporan

Verifikasi admin

Putusan kasubag

Digunakan untuk audit internal instansi.

🔒 Security Features

✅ Role Middleware
✅ IP Whitelist Admin
✅ CSP Header
✅ Status Transition Guard
✅ Transaction Database
✅ Audit Trail
✅ Request Validation

🤝 Workflow Kerja Tim

Frontend wajib menggunakan branch:

git checkout -b frontend-ui

Push perubahan:

git add .
git commit -m "UI Update"
git push

Kemudian buat:

✅ Pull Request → main

Backend akan melakukan review sebelum merge.

🧠 Catatan Penting Untuk Frontend

Backend sudah:

✅ Stable
✅ Workflow Locked
✅ Production Ready
✅ Data Safe

Frontend tidak perlu membuat logic baru.

Jika butuh data tambahan:
➡️ Request endpoint ke Backend Developer.

📦 Production Requirement (NANTI DEPLOY)

Pastikan .env production:

APP_ENV=production
APP_DEBUG=false
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=strict
LOG_LEVEL=warning
QUEUE_CONNECTION=database
👨‍💻 Backend Owner

Maintained by:

MrCatfish75-debug
Backend Developer — Sistem Pengaduan IT BPS

✅ Status Project

🟢 Backend Stable
🟢 Ready For Frontend UI/UX Development
🟢 Safe For Team Collaboration


---

## ✅ Setelah ini lakukan:

```bash
git add README.md
git commit -m "UPDATE README - FRONTEND HANDOVER GUIDE"
git push
