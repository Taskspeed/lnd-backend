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
        Schema::create('training_evaluation_speakers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_form_submission_id')->constrained('employee_form_submissions')->onDelete('cascade');
            $table->string('event')->nullable();
            $table->string('date')->nullable();
            $table->string('venue')->nullable();
            $table->string('learning_service_provider')->nullable();  
            $table->timestamps();
        });

        Schema::create('speaker_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_evaluation_speaker_id')->constrained('training_evaluation_speakers')->onDelete('cascade');
            $table->unsignedTinyInteger('mastery_of_subject')->nullable();
            $table->unsignedTinyInteger('ability_to_involve')->nullable();
            $table->unsignedTinyInteger('session_interest')->nullable();
            $table->unsignedTinyInteger('handling_questions')->nullable();
            $table->unsignedTinyInteger('voice_projection')->nullable();
            $table->unsignedTinyInteger('communication')->nullable();
            $table->unsignedTinyInteger('practical_relevance')->nullable();
            $table->unsignedTinyInteger('over_all_rating')->nullable();

            $table->text('comments')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_evaluation_speakers');
        Schema::dropIfExists('speaker_evaluations');
    }
};
