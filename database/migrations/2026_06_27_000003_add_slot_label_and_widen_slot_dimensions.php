<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_slots', function (Blueprint $table) {
            $table->string('slot_label', 100)->nullable()->after('slot_number');
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE event_slots ALTER COLUMN slot_width TYPE numeric(8, 2)');
            DB::statement('ALTER TABLE event_slots ALTER COLUMN slot_long TYPE numeric(8, 2)');
            DB::statement('ALTER TABLE event_slots ALTER COLUMN slot_width DROP NOT NULL');
            DB::statement('ALTER TABLE event_slots ALTER COLUMN slot_long DROP NOT NULL');

            return;
        }

        Schema::table('event_slots', function (Blueprint $table) {
            $table->decimal('slot_width', 8, 2)->nullable()->change();
            $table->decimal('slot_long', 8, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE event_slots ALTER COLUMN slot_width SET NOT NULL');
            DB::statement('ALTER TABLE event_slots ALTER COLUMN slot_long SET NOT NULL');
            DB::statement('ALTER TABLE event_slots ALTER COLUMN slot_width TYPE integer USING COALESCE(slot_width, 0)::integer');
            DB::statement('ALTER TABLE event_slots ALTER COLUMN slot_long TYPE integer USING COALESCE(slot_long, 0)::integer');
        } else {
            Schema::table('event_slots', function (Blueprint $table) {
                $table->integer('slot_width')->nullable(false)->default(0)->change();
                $table->integer('slot_long')->nullable(false)->default(0)->change();
            });
        }

        Schema::table('event_slots', function (Blueprint $table) {
            $table->dropColumn('slot_label');
        });
    }
};
