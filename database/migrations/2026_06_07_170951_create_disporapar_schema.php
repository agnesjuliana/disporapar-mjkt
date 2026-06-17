<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('email');
            $table->string('password_hash');
            $table->enum('role', ['ADMIN', 'EVENT_ORGANIZER', 'TENANT', 'MASYARAKAT']);
            $table->enum('status', ['ADMIN', 'EVENT_ORGANIZER', 'TENANT', 'MASYARAKAT']);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('venues', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('address');
            $table->integer('capacity');
            $table->string('description');
            $table->decimal('lowest_price', 15, 2);
            $table->decimal('highest_price', 15, 2);
            $table->timestamp('available_from');
            $table->timestamp('available_to');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tenants', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id');
            $table->string('organization_name');
            $table->string('contact_person');
            $table->string('contact_phone');
            $table->string('address');
            $table->string('registration_status');
            $table->timestamp('registered_at');
            $table->string('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('approved_by')->references('id')->on('users');
        });

        Schema::create('event_organizers', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id');
            $table->string('organization_name');
            $table->string('contact_person');
            $table->string('contact_phone');
            $table->string('address');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('events', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('description');
            $table->string('organizer_id');
            $table->enum('venue_type', ['INTERNAL', 'EXTERNAL']);
            $table->string('venue_id')->nullable();
            $table->string('external_venue_name')->nullable();
            $table->string('external_venue_address')->nullable();
            $table->integer('external_venue_capacity')->nullable();
            $table->timestamp('event_start');
            $table->timestamp('event_end');
            $table->integer('slot_size');
            $table->integer('capacity');
            $table->string('status');
            $table->string('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('organizer_id')->references('id')->on('event_organizers');
            $table->foreign('venue_id')->references('id')->on('venues');
            $table->foreign('approved_by')->references('id')->on('users');
        });

        Schema::create('venue_bookings', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('venue_id');
            $table->string('organizer_id');
            $table->string('event_id')->nullable();
            $table->timestamp('booking_start');
            $table->timestamp('booking_end');
            $table->string('status');
            $table->timestamp('requested_at');
            $table->string('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->decimal('final_price', 15, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('venue_id')->references('id')->on('venues');
            $table->foreign('organizer_id')->references('id')->on('event_organizers');
            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('approved_by')->references('id')->on('users');
        });

        Schema::create('event_slots', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('event_id');
            $table->integer('slot_number');
            $table->timestamp('date_start');
            $table->timestamp('date_end');
            $table->integer('slot_width');
            $table->integer('slot_long');
            $table->decimal('price', 15, 2);
            $table->boolean('is_booked');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('event_id')->references('id')->on('events');
        });

        Schema::create('event_registrations', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('event_id');
            $table->string('tenant_id');
            $table->enum('registration_status', ['PENDING', 'APPROVED', 'REJECTED', 'CANCELLED']);
            $table->timestamp('registered_at');
            $table->string('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->foreign('approved_by')->references('id')->on('users');
        });

        Schema::create('registration_documents', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('event_registration_id');
            $table->enum('document_type', ['KTP', 'KK', 'PHOTO', 'BUSINESS_LICENSE', 'OTHER']);
            $table->text('file_url');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('event_registration_id')->references('id')->on('event_registrations');
        });

        Schema::create('registration_slots', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('event_registration_id');
            $table->string('slot_id');
            $table->enum('status', ['ASSIGNED', 'CANCELLED']);
            $table->timestamp('assigned_at');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('event_registration_id')->references('id')->on('event_registrations');
            $table->foreign('slot_id')->references('id')->on('event_slots');
        });

        Schema::create('registration_attendances', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('event_registration_id');
            $table->enum('attendance_status', ['NOT_CHECKED_IN', 'PRESENT', 'ABSENT']);
            $table->timestamp('checked_in_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('event_registration_id')->references('id')->on('event_registrations');
        });

        Schema::create('participant_registration', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('event_id');
            $table->string('user_id');
            $table->timestamp('registered_at');
            $table->enum('attendance_status', ['NOT_CHECKED_IN', 'PRESENT', 'ABSENT']);
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participant_registration');
        Schema::dropIfExists('registration_attendances');
        Schema::dropIfExists('registration_slots');
        Schema::dropIfExists('registration_documents');
        Schema::dropIfExists('event_registrations');
        Schema::dropIfExists('event_slots');
        Schema::dropIfExists('venue_bookings');
        Schema::dropIfExists('events');
        Schema::dropIfExists('event_organizers');
        Schema::dropIfExists('tenants');
        Schema::dropIfExists('venues');
        Schema::dropIfExists('users');
    }
};
