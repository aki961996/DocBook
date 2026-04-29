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
        Schema::create('doctors', function (Blueprint $table) {
          $table->uuid('id')->primary();
            $table->foreignUuid('hospital_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('department_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('qualification');        // e.g. MBBS, MD
            $table->string('specialization');       // e.g. Fetal Medicine
            $table->integer('experience_years')->default(0);
            $table->string('photo')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->decimal('consultation_fee', 8, 2)->default(0);
            $table->text('bio')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};   