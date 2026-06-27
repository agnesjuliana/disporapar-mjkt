<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->unsignedInteger('requested_slot_count')->default(1)->after('notes');
            $table->json('requested_slot_ids')->nullable()->after('requested_slot_count');
        });
    }

    public function down(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropColumn(['requested_slot_count', 'requested_slot_ids']);
        });
    }
};
