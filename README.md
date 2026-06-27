# Event Disporapar

Event Disporapar adalah aplikasi Laravel untuk pengelolaan event daerah Mojokerto. Aplikasi memakai role `ADMIN`, `EVENT_ORGANIZER`, `TENANT`, dan `MASYARAKAT`.

## Fitur Utama

- Registrasi dan login berbasis role.
- Verifikasi email dengan OTP untuk registrasi Masyarakat, Event Organizer, dan Tenant.
- Dashboard dan sidebar berbasis role.
- Admin venue CRUD dan review booking venue.
- Event Organizer membuat event, booking venue, mengelola slot, tenant, dan daftar pengunjung.
- Tenant mencari event, memilih preferensi slot, dan mengajukan booking tenant.
- Masyarakat melihat kalender event dan mendaftar sebagai peserta.

## Teknologi

- PHP `^8.3`
- Laravel
- PostgreSQL
- Vite
- Tailwind CSS
- Mailtrap Email Sandbox untuk email OTP development

## Persiapan

Pastikan perangkat memiliki:

- PHP 8.3 atau lebih baru
- Composer
- Node.js dan npm
- PostgreSQL
- Akun Mailtrap untuk menangkap email OTP saat development

## Setup Project

Masuk ke direktori Laravel:

```bash
cd laravel-disporapar
```

Install dependency:

```bash
composer install
npm install
```

Salin environment:

```bash
cp .env.example .env
```

Generate app key:

```bash
php artisan key:generate
```

Sesuaikan konfigurasi database PostgreSQL di `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=events_db01
DB_USERNAME=tbs_user
DB_PASSWORD=s3curepass
```

Buat database dan user PostgreSQL sesuai konfigurasi tersebut, atau ubah `.env` mengikuti database lokal Anda.

Jalankan migration dan seeder:

```bash
php artisan migrate --seed
```

Jalankan aplikasi:

```bash
php artisan serve
```

Jalankan Vite di terminal lain:

```bash
npm run dev
```

Aplikasi dapat dibuka di:

```text
http://127.0.0.1:8000
```

## Setup Mailtrap Untuk OTP

Registrasi akun baru Masyarakat, Event Organizer, dan Tenant akan mengirim OTP ke email. Untuk development, gunakan Mailtrap Email Sandbox supaya email tidak dikirim ke inbox asli.

Langkah setup:

1. Buat akun di `https://mailtrap.io`.
2. Masuk ke menu **Email Testing** atau **Email Sandbox**.
3. Buka inbox sandbox.
4. Pilih integrasi SMTP.
5. Salin SMTP username dan password dari Mailtrap.
6. Isi `.env`:

```env
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=isi_dari_mailtrap
MAIL_PASSWORD=isi_dari_mailtrap
MAIL_FROM_ADDRESS="no-reply@disporapar.test"
MAIL_FROM_NAME="${APP_NAME}"
```

Setelah mengubah `.env`, bersihkan cache konfigurasi:

```bash
php artisan config:clear
```

Saat user baru register, buka inbox Mailtrap untuk melihat email OTP. OTP berlaku selama 10 menit.

## Email Verification Flow

Flow registrasi user baru:

1. User memilih role dan mengisi form registrasi.
2. Sistem membuat user dengan `is_verified = false`.
3. Sistem membuat OTP 6 digit, menyimpan hash OTP di database, dan mengirim OTP ke Mailtrap.
4. User diarahkan ke halaman `/verify-email`.
5. User memasukkan OTP.
6. Jika OTP valid dan belum kedaluwarsa, `is_verified` berubah menjadi `true`.
7. User baru bisa login setelah email terverifikasi.

Login user yang belum terverifikasi akan diblokir dan sistem akan mengirim OTP baru.

Seeder user bawaan sudah dibuat sebagai verified agar akun development tetap bisa langsung login.

## Akun Seeder

Semua akun seeder memakai password:

```text
password
```

| Role | Email |
| --- | --- |
| Admin | `admin@disporapar.test` |
| Event Organizer | `organizer@disporapar.test` |
| Tenant | `tenant@disporapar.test` |
| Masyarakat | `masyarakat@disporapar.test` |

## Perintah Database

Menjalankan migration:

```bash
php artisan migrate
```

Menjalankan seeder:

```bash
php artisan db:seed
```

Reset database dan isi ulang data awal:

```bash
php artisan migrate:fresh --seed
```

## Perintah Development

Menjalankan Laravel, queue listener, log viewer, dan Vite secara bersamaan:

```bash
composer run dev
```

Build asset frontend:

```bash
npm run build
```

Menjalankan test:

```bash
composer test
```

## Struktur Modul

- `app/Models`: model utama seperti `User`, `Event`, `Venue`, `Tenant`, `EventOrganizer`, dan model registrasi.
- `app/Http/Controllers`: controller auth, dashboard, admin, EO, tenant, masyarakat, venue, event, booking, slot, dan registrasi.
- `database/migrations`: skema database.
- `database/seeders`: data awal development.
- `resources/views`: Blade views untuk auth, dashboard, admin, EO, tenant, dan masyarakat.

## Catatan Keamanan

- Jangan commit `.env` berisi credential asli.
- `.env.example` hanya berisi placeholder Mailtrap.
- OTP email disimpan sebagai hash di database, bukan plain text.
- Untuk production, ganti Mailtrap sandbox dengan provider email produksi.
