<?php

namespace App\Support;

class RoleMenu
{
    /**
     * @return array<string, mixed>
     */
    public static function for(?string $role): array
    {
        return match ($role) {
            'ADMIN' => [
                'role' => 'ADMIN',
                'label' => 'Admin',
                'subtitle' => 'Admin Panel',
                'icon' => 'zap',
                'color' => 'bg-indigo-500',
                'accent' => 'indigo',
                'fallbackInitial' => 'A',
                'items' => [
                    ['icon' => 'home', 'label' => 'Home', 'page' => 'home', 'href' => '/dashboard', 'description' => 'Ringkasan profil dan pintasan menu admin.'],
                    ['icon' => 'calendar-days', 'label' => 'Kalender Acara', 'page' => 'calendar', 'href' => '/dashboard/calendar', 'description' => 'Pantau event yang sedang berjalan dan jadwal daerah.'],
                    ['icon' => 'building-2', 'label' => 'Manajemen Venue', 'page' => 'venues', 'href' => '/admin/venues', 'description' => 'Kelola data venue, kapasitas, harga, dan ketersediaan.'],
                    ['icon' => 'calendar-check', 'label' => 'Booking Venue', 'page' => 'venue-bookings', 'href' => '/admin/venue-bookings', 'description' => 'Tinjau dan setujui permintaan booking venue.'],
                    ['icon' => 'calendar-plus', 'label' => 'Manajemen Event', 'page' => 'events', 'href' => '/admin/events', 'description' => 'Verifikasi dan kelola pengajuan event baru.'],
                    ['icon' => 'users', 'label' => 'Daftar Pengguna', 'page' => 'users', 'href' => '/users', 'description' => 'Kelola akun admin, EO, tenant, dan masyarakat.'],
                ],
            ],
            'EVENT_ORGANIZER' => [
                'role' => 'EVENT_ORGANIZER',
                'label' => 'Event Organizer',
                'subtitle' => 'Portal Event Organizer',
                'icon' => 'calendar-range',
                'color' => 'bg-emerald-500',
                'accent' => 'emerald',
                'fallbackInitial' => 'E',
                'items' => [
                    ['icon' => 'home', 'label' => 'Home', 'page' => 'home', 'href' => '/dashboard', 'description' => 'Ringkasan profil dan pintasan menu event organizer.'],
                    ['icon' => 'calendar-days', 'label' => 'Kalender Acara', 'page' => 'calendar', 'href' => '/dashboard/calendar', 'description' => 'Pantau event berjalan dan agenda daerah.'],
                    [
                        'icon' => 'calendar-plus',
                        'label' => 'Manajemen Event',
                        'page' => 'events',
                        'href' => '/eo/events',
                        'description' => 'Buat dan kelola event yang diajukan oleh EO.',
                        'children' => [
                            ['icon' => 'users', 'label' => 'Manajemen Pengunjung', 'page' => 'event-visitors', 'href' => '/participant-registrations?event_id={event}', 'description' => 'Kelola pendaftaran dan kehadiran pengunjung.'],
                            ['icon' => 'store', 'label' => 'Manajemen Tenant', 'page' => 'event-tenants', 'href' => '/eo/events/{event}/tenant-registrations', 'description' => 'Kelola tenant UMKM yang mendaftar ke event.'],
                            ['icon' => 'grid-2x2', 'label' => 'Manajemen Slot', 'page' => 'event-slots', 'href' => '/eo/events/{event}/slots', 'description' => 'Atur slot tenant dalam event.'],
                            ['icon' => 'scan-line', 'label' => 'Absensi Event', 'page' => 'attendance', 'href' => '/registration-attendances?event_id={event}', 'description' => 'Pantau check-in dan kehadiran peserta.'],
                        ],
                    ],
                    ['icon' => 'building-2', 'label' => 'Daftar Venue', 'page' => 'venues', 'href' => '/eo/daftar-venue', 'description' => 'Cari venue yang tersedia untuk event.'],
                    ['icon' => 'calendar-check', 'label' => 'Venue Booking', 'page' => 'venue-bookings', 'href' => '/eo/venue-booking', 'description' => 'Ajukan dan pantau booking venue.'],
                ],
            ],
            'MASYARAKAT' => [
                'role' => 'MASYARAKAT',
                'label' => 'Masyarakat',
                'subtitle' => 'Portal Masyarakat',
                'icon' => 'users',
                'color' => 'bg-indigo-500',
                'accent' => 'indigo',
                'fallbackInitial' => 'M',
                'items' => [
                    ['icon' => 'home', 'label' => 'Home', 'page' => 'home', 'href' => '/dashboard', 'description' => 'Ringkasan profil dan pintasan menu masyarakat.'],
                    ['icon' => 'calendar-days', 'label' => 'Kalender Acara', 'page' => 'calendar', 'href' => '/dashboard/calendar', 'description' => 'Lihat jadwal event daerah Mojokerto.'],
                    ['icon' => 'history', 'label' => 'Event History', 'page' => 'history', 'href' => '/participant-registrations', 'description' => 'Lihat riwayat kehadiran dan event yang pernah diikuti.'],
                ],
            ],
            default => [
                'role' => 'TENANT',
                'label' => 'Tenant UMKM',
                'subtitle' => 'Portal Tenant UMKM',
                'icon' => 'store',
                'color' => 'bg-orange-500',
                'accent' => 'orange',
                'fallbackInitial' => 'T',
                'items' => [
                    ['icon' => 'home', 'label' => 'Home', 'page' => 'home', 'href' => '/dashboard', 'description' => 'Ringkasan profil dan pintasan menu tenant UMKM.'],
                    ['icon' => 'calendar-days', 'label' => 'Kalender Acara', 'page' => 'calendar', 'href' => '/dashboard/calendar', 'description' => 'Lihat event berjalan dan agenda daerah.'],
                    ['icon' => 'calendar-search', 'label' => 'Daftar Event', 'page' => 'events', 'href' => '/events', 'description' => 'Temukan rekomendasi event untuk tenant Anda.'],
                    ['icon' => 'clipboard-list', 'label' => 'Tenant Booking', 'page' => 'tenant-bookings', 'href' => '/event-registrations', 'description' => 'Kelola booking tenant pada event yang diikuti.'],
                ],
            ],
        };
    }
}
