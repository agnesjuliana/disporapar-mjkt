<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        // Fixed (non-random) id so CategorySeeder can safely re-sync this row
        // by the same id on every `db:seed` run without orphaning the
        // `events.category_id` foreign key it gets backfilled into below.
        $defaultCategoryId = 'CAT-UMUM';

        DB::table('categories')->insert([
            'id' => $defaultCategoryId,
            'name' => 'Umum',
            'slug' => 'umum',
            'icon' => 'tag',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Schema::table('events', function (Blueprint $table) {
            $table->string('category_id')->nullable()->after('organizer_id');
        });

        DB::table('events')->whereNull('category_id')->update(['category_id' => $defaultCategoryId]);

        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE events ALTER COLUMN category_id SET NOT NULL');
        } else {
            Schema::table('events', function (Blueprint $table) {
                $table->string('category_id')->nullable(false)->change();
            });
        }

        Schema::table('events', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories');
        });

        // Auto-increment id (bukan string UUID) karena tabel ini murni pivot
        // many-to-many yang dikelola lewat sync()/attach() Eloquent — bukan
        // entitas berdiri sendiri yang di-create manual seperti tabel lain.
        Schema::create('user_category_interests', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('category_id');
            $table->timestamps();

            $table->unique(['user_id', 'category_id']);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
        });

        Schema::create('event_click_logs', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id');
            $table->string('event_id');
            $table->string('category_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_click_logs');
        Schema::dropIfExists('user_category_interests');

        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::dropIfExists('categories');
    }
};
