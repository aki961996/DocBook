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
        Schema::create('doctor_slots', function (Blueprint $table) {
           $table->uuid('id')->primary();
            $table->foreignUuid('doctor_id')->constrained()->cascadeOnDelete();
            $table->date('slot_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('max_patients')->default(1);
            $table->boolean('is_booked')->default(false);
            $table->boolean('is_blocked')->default(false); // admin can block a slot
            $table->timestamps();

            $table->index(['doctor_id', 'slot_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_slots');
    }
};
