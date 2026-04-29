<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Admin users (Breeze / standard auth)
        Schema::create('admin_users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('hospital_admin'); // super_admin | hospital_admin
            $table->foreignUuid('hospital_id')->nullable()->constrained()->nullOnDelete();
            $table->rememberToken();
            $table->timestamps();
        });

        // Patients (WhatsApp OTP based)
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->nullable();
            $table->string('phone', 20)->unique();   // WhatsApp number
            $table->string('email')->nullable();
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // OTP store (for both admin forgot-pw and patient login)
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20)->index();
            $table->string('otp', 6);
            $table->timestamp('expires_at');
            $table->boolean('is_used')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otps');
        Schema::dropIfExists('users');
        Schema::dropIfExists('admin_users');
    }
};
