# 🚀 SISTEM PENGADUAN IT BPS KOTA PALANGKA RAYA

## 🧠 Fullstack Web Application (Laravel 12)

Sistem Pengaduan IT berbasis **Laravel 12** untuk pengelolaan laporan kerusakan Barang Milik Negara (BMN).

Aplikasi ini mencakup:

✅ Backend (API + Business Logic)
✅ Dashboard Web (Admin, Kasubag, Pegawai)
✅ Workflow Management System
✅ Role-Based Access Control

---

# 🧱 WORKFLOW SISTEM

```text
Pegawai
   ↓
MENUNGGU_REVIEW_ADMIN
   ↓
Admin IT Verifikasi
   ↓
MENUNGGU_KEPUTUSAN_KASUBAG
   ↓
Kasubag Decision
   ↓
DIKIRIM_VENDOR / MENUNGGU_PENGADAAN
   ↓
SELESAI / DITOLAK
```

⚠️ Workflow dikontrol backend dan **tidak boleh dimanipulasi frontend**.

---

# 👥 ROLE SYSTEM

| Role    | Akses                                     |
| ------- | ----------------------------------------- |
| Pegawai | Membuat laporan & melihat laporan sendiri |
| Admin   | Verifikasi, kelola laporan, monitoring    |
| Kasubag | Pengambilan keputusan (approve/reject)    |

---

# 📊 DASHBOARD FEATURES

### 🧑‍💼 Admin

* Statistik laporan
* Verifikasi laporan
* Monitoring aktivitas
* Kelola pengguna

### 🧑‍💼 Kasubag

* Review laporan dari admin
* Keputusan laporan (approve/reject)
* Monitoring hasil

### 👨‍💻 Pegawai

* Dashboard statistik global
* Laporan terbaru
* Buat laporan

---

# ⚙️ TECH STACK

* Laravel 12
* PHP 8.2+
* MySQL
* Laravel Breeze (Auth)
* Middleware Role-Based
* Blade + Tailwind CSS
* Audit Logging System

---

# 🔐 AUTHENTICATION SYSTEM

Menggunakan:

✔ Laravel Auth (Session Based)
✔ Middleware Role (admin / pegawai / kasubag)

---

# 🔒 MIDDLEWARE ARCHITECTURE

```text
auth → role middleware → controller
```

Contoh:

```php
Route::middleware(['auth','admin'])
```

⚠️ Penting:

* Tidak menggunakan `abort(403)`
* Semua role redirect ke dashboard masing-masing
* Menghindari error Forbidden & UX buruk

---

# 🌐 ROUTING SYSTEM

```text
/ → redirect ke dashboard sesuai role
/dashboard → auto redirect role
/admin → area admin
/pegawai → area pegawai
/kasubag → area kasubag
```

---

# 🚀 INSTALLATION

Clone repository:

```bash
git clone https://github.com/mrcatfish75-debug/pengaduan-it-bps.git
cd pengaduan-it-bps
```

Install dependency:

```bash
composer install
npm install
```

Setup environment:

```bash
cp .env.example .env
php artisan key:generate
```

Setup database:

```bash
php artisan migrate
php artisan db:seed
```

Run server:

```bash
php artisan serve
npm run dev
```

---

# ⚠️ PENTING (WAJIB DILAKUKAN)

Setelah clone / perubahan:

```bash
php artisan optimize:clear
```

👉 Untuk menghindari bug seperti:

* middleware tidak terbaca
* route lama masih dipakai
* error “Target class does not exist”

---

# 🔐 DEFAULT ACCOUNT

| Role    | Email                                         | Password |
| ------- | --------------------------------------------- | -------- |
| Admin   | [admin@bps.go.id](mailto:admin@bps.go.id)     | password |
| Kasubag | [kasubag@bps.go.id](mailto:kasubag@bps.go.id) | password |
| Pegawai | [pegawai@bps.go.id](mailto:pegawai@bps.go.id) | password |

---

# 📦 STRUKTUR PENTING PROJECT

```text
app/
 └── Http/
     ├── Controllers/
     ├── Middleware/
routes/
resources/views/
bootstrap/app.php   ← middleware register (IMPORTANT)
```

---

# ⚠️ CATATAN PENTING UNTUK DEVELOPER

### ❗ Middleware harus didaftarkan di:

```php
bootstrap/app.php
```

Contoh:

```php
$middleware->alias([
    'admin' => AdminMiddleware::class,
]);
```

---

### ❗ Jangan gunakan:

```php
abort(403);
```

Gunakan:

```php
return redirect()->route('dashboard');
```

---

### ❗ Jangan tambahkan middleware fiktif

Contoh yang SALAH:

```php
'admin.ip'
'admin.log'
```

Jika belum dibuat → akan error.

---

# 🧭 FRONTEND NOTE

Frontend:

✔ Bisa pakai Blade (current)
✔ Bisa diubah ke Vue / React
✔ Tidak perlu handle workflow logic

---

# 📊 PROJECT STATUS

| Feature         | Status    |
| --------------- | --------- |
| Authentication  | ✅ Stable  |
| Role Middleware | ✅ Fixed   |
| Dashboard       | ✅ Active  |
| Workflow        | ✅ Running |
| API             | ✅ Ready   |
| Bug Middleware  | ✅ Fixed   |

---

# 👨‍💻 BACKEND OWNER

**MrCatfish75-debug**
Backend Developer — Sistem Pengaduan IT BPS

---

# 🚀 FUTURE DEVELOPMENT

* 📊 Chart dashboard (analytics)
* 🔐 Permission system granular
* 🌐 API full separation
* 📱 Mobile app integration
* 🧠 SLA & performance tracking

---

# 🏁 FINAL NOTE

Project ini sudah:

✔ Stabil
✔ Bisa digunakan production (minor adjustment)
✔ Siap dikembangkan tim lain tanpa kebingungan

---
