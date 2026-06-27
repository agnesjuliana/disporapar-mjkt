<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_status_check');
            DB::statement('ALTER TABLE users ALTER COLUMN status TYPE varchar(255)');
        }

        DB::table('users')
            ->whereIn('status', ['ADMIN', 'EVENT_ORGANIZER', 'TENANT', 'MASYARAKAT'])
            ->update(['status' => 'ACTIVE']);
    }

    public function down(): void
    {
        DB::table('users')->update(['status' => DB::raw('role')]);
    }
};
