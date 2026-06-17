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
            DB::statement('ALTER TABLE venues ALTER COLUMN lowest_price TYPE numeric(15, 2)');
            DB::statement('ALTER TABLE venues ALTER COLUMN highest_price TYPE numeric(15, 2)');
            DB::statement('ALTER TABLE venue_bookings ALTER COLUMN final_price TYPE numeric(15, 2)');
            DB::statement('ALTER TABLE event_slots ALTER COLUMN price TYPE numeric(15, 2)');

            return;
        }

        Schema::table('venues', function ($table) {
            $table->decimal('lowest_price', 15, 2)->change();
            $table->decimal('highest_price', 15, 2)->change();
        });

        Schema::table('venue_bookings', function ($table) {
            $table->decimal('final_price', 15, 2)->nullable()->change();
        });

        Schema::table('event_slots', function ($table) {
            $table->decimal('price', 15, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE venues ALTER COLUMN lowest_price TYPE numeric(10, 2)');
            DB::statement('ALTER TABLE venues ALTER COLUMN highest_price TYPE numeric(10, 2)');
            DB::statement('ALTER TABLE venue_bookings ALTER COLUMN final_price TYPE numeric(10, 2)');
            DB::statement('ALTER TABLE event_slots ALTER COLUMN price TYPE numeric(10, 2)');

            return;
        }

        Schema::table('venues', function ($table) {
            $table->decimal('lowest_price', 10, 2)->change();
            $table->decimal('highest_price', 10, 2)->change();
        });

        Schema::table('venue_bookings', function ($table) {
            $table->decimal('final_price', 10, 2)->nullable()->change();
        });

        Schema::table('event_slots', function ($table) {
            $table->decimal('price', 10, 2)->change();
        });
    }
};
