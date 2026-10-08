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
        Schema::create('employee_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events');   // NO ACTION (default)
            $table->foreignId('event_schedule_id')->constrained('event_schedules')->onDelete('cascade');
            $table->string('control_no')->nullable();
            $table->string('remarks')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_document_id')->constrained('employee_documents')->onDelete('cascade');
            $table->string('file_path')->nullable();
            $table->string('title')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        //child muna bago parent
        Schema::dropIfExists('documents');
        Schema::dropIfExists('employee_documents');
    }
};
