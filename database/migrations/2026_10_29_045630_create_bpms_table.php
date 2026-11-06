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
        Schema::create('bpms', function (Blueprint $table) {
            $table->id();
            $table->string('controlNo')->nullable();
            $table->string('systolic_pressure')->nullable();
            $table->string('diastolic_pressure')->nullable();
            $table->foreignId('bpm_date_id')->constrained('bpm_dates')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bpms');
    }
};
