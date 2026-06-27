<?php

namespace Database\Seeders;

use App\Models\EventOrganizer;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'id' => 'USR-ADMIN-001',
                'name' => 'Admin Disporapar',
                'email' => 'admin@disporapar.test',
                'password_hash' => 'password',
                'role' => 'ADMIN',
                'status' => 'ACTIVE',
                'is_verified' => true,
            ],
            [
                'id' => 'USR-EO-001',
                'name' => 'Event Organizer',
                'email' => 'organizer@disporapar.test',
                'password_hash' => 'password',
                'role' => 'EVENT_ORGANIZER',
                'status' => 'ACTIVE',
                'is_verified' => true,
            ],
            [
                'id' => 'USR-TENANT-001',
                'name' => 'Tenant',
                'email' => 'tenant@disporapar.test',
                'password_hash' => 'password',
                'role' => 'TENANT',
                'status' => 'ACTIVE',
                'is_verified' => true,
            ],
            [
                'id' => 'USR-MASYARAKAT-001',
                'name' => 'Masyarakat',
                'email' => 'masyarakat@disporapar.test',
                'password_hash' => 'password',
                'role' => 'MASYARAKAT',
                'status' => 'ACTIVE',
                'is_verified' => true,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['id' => $user['id']],
                $user,
            );
        }

        EventOrganizer::updateOrCreate(
            ['id' => 'EO-001'],
            [
                'user_id' => 'USR-EO-001',
                'organization_name' => 'Event Organizer Mojokerto',
                'contact_person' => 'Event Organizer',
                'contact_phone' => '081234567890',
                'address' => 'Mojokerto',
            ],
        );

        Tenant::updateOrCreate(
            ['id' => 'TNT-001'],
            [
                'user_id' => 'USR-TENANT-001',
                'organization_name' => 'Tenant UMKM Mojokerto',
                'contact_person' => 'Tenant',
                'contact_phone' => '081234567891',
                'address' => 'Mojokerto',
                'registration_status' => 'APPROVED',
                'registered_at' => now(),
                'approved_by' => 'USR-ADMIN-001',
                'approved_at' => now(),
            ],
        );

        $venues = [
            [
                'id' => 'VENUE-001',
                'name' => 'Gedung Kesenian Mojokerto',
                'address' => 'Jl. Benteng Pancasila, Mojokerto',
                'capacity' => 500,
                'description' => 'Venue indoor untuk event seni, pameran, dan kegiatan komunitas.',
                'lowest_price' => 2500000,
                'highest_price' => 7500000,
                'available_from' => now()->startOfDay(),
                'available_to' => now()->addMonths(6)->endOfDay(),
            ],
            [
                'id' => 'VENUE-002',
                'name' => 'Lapangan Raden Wijaya',
                'address' => 'Pusat Kota Mojokerto',
                'capacity' => 2000,
                'description' => 'Area outdoor untuk festival, bazar, dan event berskala besar.',
                'lowest_price' => 5000000,
                'highest_price' => 15000000,
                'available_from' => now()->startOfDay(),
                'available_to' => now()->addMonths(6)->endOfDay(),
            ],
            [
                'id' => 'VENUE-003',
                'name' => 'Aula Disporapar',
                'address' => 'Kantor Disporapar Mojokerto',
                'capacity' => 150,
                'description' => 'Aula rapat dan workshop untuk kegiatan internal maupun publik.',
                'lowest_price' => 1000000,
                'highest_price' => 3000000,
                'available_from' => now()->startOfDay(),
                'available_to' => now()->addMonths(6)->endOfDay(),
            ],
        ];

        foreach ($venues as $venue) {
            Venue::updateOrCreate(
                ['id' => $venue['id']],
                $venue,
            );
        }
    }
}
