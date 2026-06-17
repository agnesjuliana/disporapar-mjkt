<?php

namespace Database\Seeders;

use App\Models\User;
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
                'status' => 'ADMIN',
            ],
            [
                'id' => 'USR-EO-001',
                'name' => 'Event Organizer',
                'email' => 'organizer@disporapar.test',
                'password_hash' => 'password',
                'role' => 'EVENT_ORGANIZER',
                'status' => 'EVENT_ORGANIZER',
            ],
            [
                'id' => 'USR-TENANT-001',
                'name' => 'Tenant',
                'email' => 'tenant@disporapar.test',
                'password_hash' => 'password',
                'role' => 'TENANT',
                'status' => 'TENANT',
            ],
            [
                'id' => 'USR-MASYARAKAT-001',
                'name' => 'Masyarakat',
                'email' => 'masyarakat@disporapar.test',
                'password_hash' => 'password',
                'role' => 'MASYARAKAT',
                'status' => 'MASYARAKAT',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['id' => $user['id']],
                $user,
            );
        }
    }
}
