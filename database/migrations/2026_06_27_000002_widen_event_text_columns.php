<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE events ALTER COLUMN description TYPE text');
            DB::statement('ALTER TABLE events ALTER COLUMN external_venue_address TYPE text');

            return;
        }

        Schema::table('events', function ($table) {
            $table->text('description')->change();
            $table->text('external_venue_address')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE events ALTER COLUMN description TYPE varchar(255)');
            DB::statement('ALTER TABLE events ALTER COLUMN external_venue_address TYPE varchar(255)');

            return;
        }

        Schema::table('events', function ($table) {
            $table->string('description')->change();
            $table->string('external_venue_address')->nullable()->change();
        });
    }
};

