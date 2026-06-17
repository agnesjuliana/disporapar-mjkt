# Event Disporapar

Event Disporapar adalah aplikasi web berbasis Laravel untuk membantu pengelolaan event, venue, tenant, event organizer, dan registrasi peserta. Aplikasi ini memakai sistem role sehingga tampilan dashboard dan akses fitur dapat dibedakan berdasarkan jenis pengguna.

## Fitur Utama

- Autentikasi login dan registrasi untuk masyarakat, tenant, dan event organizer.
- Dashboard berbasis role untuk `ADMIN`, `EVENT_ORGANIZER`, `TENANT`, dan `MASYARAKAT`.
- Manajemen venue oleh admin.
- Pengajuan booking venue oleh event organizer.
- Persetujuan atau penolakan booking venue oleh admin.
- Struktur data untuk event, slot event, registrasi tenant, dokumen registrasi, kehadiran, dan registrasi peserta.

## Teknologi

- PHP `^8.3`
- Laravel `^13.8`
- SQLite sebagai konfigurasi database default
- Vite
- Tailwind CSS

## Persiapan

Pastikan perangkat sudah memiliki:

- PHP 8.3 atau lebih baru
- Composer
- Node.js dan npm
- SQLite extension untuk PHP

## Cara Menjalankan Project

Masuk ke direktori aplikasi Laravel:

```bash
cd laravel-disporapar
```

Install dependency PHP:

```bash
composer install
```

Install dependency frontend:

```bash
npm install
```

Salin file environment:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Pastikan database SQLite tersedia:

```bash
touch database/database.sqlite
```

Jalankan migration dan seeder:

```bash
php artisan migrate --seed
```

Jalankan server Laravel:

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

## Akun Seeder

Seeder membuat akun awal berikut. Semua akun memakai password:

```text
password
```

| Role | Email |
| --- | --- |
| Admin | `admin@disporapar.test` |
| Event Organizer | `organizer@disporapar.test` |
| Tenant | `tenant@disporapar.test` |
| Masyarakat | `masyarakat@disporapar.test` |

Seeder utama berada di `database/seeders/DatabaseSeeder.php` dan memanggil `UserSeeder`.

## Perintah Database

Menjalankan migration saja:

```bash
php artisan migrate
```

Menjalankan seeder saja:

```bash
php artisan db:seed
```

Reset database, jalankan ulang migration, lalu isi data seeder:

```bash
php artisan migrate:fresh --seed
```

## Perintah Development

Menjalankan Laravel, queue listener, log viewer, dan Vite secara bersamaan:

```bash
composer run dev
```

Build asset frontend untuk produksi:

```bash
npm run build
```

Menjalankan test:

```bash
composer test
```

## Struktur Modul

- `app/Models` berisi model utama seperti `User`, `Venue`, `Event`, `Tenant`, `EventOrganizer`, dan model registrasi.
- `app/Http/Controllers` berisi controller untuk autentikasi, dashboard, venue, booking venue, event, dan registrasi.
- `database/migrations` berisi skema tabel aplikasi.
- `database/seeders` berisi data awal aplikasi.
- `resources/views` berisi halaman Blade untuk landing page, auth, dashboard, admin, dan event organizer.

## Catatan Environment

Konfigurasi default memakai SQLite:

```env
DB_CONNECTION=sqlite
```

Jika ingin memakai database lain, ubah konfigurasi `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di file `.env`, lalu jalankan ulang migration.
