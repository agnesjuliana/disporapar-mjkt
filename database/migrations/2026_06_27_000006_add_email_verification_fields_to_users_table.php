<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_verified')->default(false)->after('status');
            $table->string('email_verification_otp_hash')->nullable()->after('is_verified');
            $table->timestamp('email_verification_otp_expires_at')->nullable()->after('email_verification_otp_hash');
        });

        DB::table('users')->update(['is_verified' => true]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_verified',
                'email_verification_otp_hash',
                'email_verification_otp_expires_at',
            ]);
        });
    }
};
