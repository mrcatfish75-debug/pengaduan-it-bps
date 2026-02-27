# 🚀 SISTEM PENGADUAN IT BPS KOTA PALANGKA RAYA

## Backend API Service (Laravel 12)

Sistem Pengaduan IT berbasis **Laravel 12 REST API** untuk pengelolaan laporan kerusakan Barang Milik Negara (BMN).

Backend ini bertindak sebagai:

✅ Business Logic Server
✅ Workflow Engine
✅ Authentication Server
✅ Data Authority
✅ REST API Provider

Frontend **bebas menggunakan framework apa pun**.

---

# 🧱 WORKFLOW SISTEM

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
SELESAI / DITOLAK

⚠️ Workflow dikontrol backend dan **tidak boleh dimanipulasi frontend**.

---

# ⚙️ TECH STACK

* Laravel 12
* PHP 8.2+
* MySQL
* Laravel Sanctum
* Role Based Access Control
* Middleware Security
* Audit Logging System

---

# 🌐 BASE API URL

```
http://127.0.0.1:8000/api
```

---

# 🔐 AUTHENTICATION (SANCTUM)

Semua endpoint menggunakan Bearer Token.

---

## LOGIN

POST `/login`

```json
{
  "email": "admin@bps.go.id",
  "password": "password"
}
```

Response:

```json
{
  "user": {},
  "token": "TOKEN"
}
```

---

## HEADER WAJIB

```
Authorization: Bearer TOKEN
Accept: application/json
```

---

# 👤 USER API

| Method | Endpoint | Function     |
| ------ | -------- | ------------ |
| GET    | /me      | Current user |
| POST   | /logout  | Logout       |

---

# 🧑‍💼 ADMIN API

| Method | Endpoint            | Function            |
| ------ | ------------------- | ------------------- |
| GET    | /admin/dashboard    | Statistik dashboard |
| GET    | /admin/laporan      | List laporan        |
| GET    | /admin/laporan/{id} | Detail laporan      |

---

# 🧭 FRONTEND INTEGRATION FLOW

1. Login
2. Simpan token
3. Kirim token di header
4. Consume API

Example:

```javascript
axios.get("/api/admin/dashboard",{
 headers:{
   Authorization:`Bearer ${token}`
 }
});
```

---

# 🚀 INSTALLATION

Clone:

```
git clone https://github.com/mrcatfish75-debug/pengaduan-it-bps.git
```

Install:

```
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

---

# 🔐 DEFAULT ACCOUNT

| Role    | Email                                         | Password |
| ------- | --------------------------------------------- | -------- |
| Admin   | [admin@bps.go.id](mailto:admin@bps.go.id)     | password |
| Kasubag | [kasubag@bps.go.id](mailto:kasubag@bps.go.id) | password |
| Pegawai | [pegawai@bps.go.id](mailto:pegawai@bps.go.id) | password |

---

# ⚠️ FRONTEND TEAM NOTE

Backend ini:

✅ API ONLY
✅ Tidak menggunakan Blade UI
✅ Workflow terkunci
✅ Production Ready

Frontend **tidak perlu membuat logic backend**.

Jika membutuhkan data tambahan → request endpoint baru.

---

# 📦 PRODUCTION REQUIREMENT

```
APP_ENV=production
APP_DEBUG=false
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=strict
LOG_LEVEL=warning
QUEUE_CONNECTION=database
```

---

# 👨‍💻 BACKEND OWNER

MrCatfish75-debug
Backend Developer — Sistem Pengaduan IT BPS

---

# ✅ PROJECT STATUS

🟢 Backend Stable
🟢 API Ready
🟢 Frontend Independent
🟢 Ready For Team Collaboration
