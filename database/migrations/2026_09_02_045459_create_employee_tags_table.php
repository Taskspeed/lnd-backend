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
        Schema::create('employee_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_schedule_id')->constrained('event_schedules')->onDelete('cascade');
            $table->string('control_no')->nullable();
            $table->string('name')->nullable();
            $table->string('office')->nullable();
            $table->string('position')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_tags');
    }
};
