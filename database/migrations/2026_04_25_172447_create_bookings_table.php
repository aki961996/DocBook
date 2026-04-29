<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('doctor_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('slot_id')->constrained('doctor_slots')->cascadeOnDelete();
            $table->foreignUuid('hospital_id')->constrained()->cascadeOnDelete();
            $table->string('booking_token')->unique();   // shareable token for patient
            $table->string('patient_name');
            $table->string('patient_phone', 20);
            $table->integer('patient_age')->nullable();
            $table->string('patient_gender')->nullable();
            $table->text('reason')->nullable();
            $table->enum('status', ['pending','confirmed','completed','cancelled'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('booked_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['hospital_id', 'status']);
            $table->index(['doctor_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
