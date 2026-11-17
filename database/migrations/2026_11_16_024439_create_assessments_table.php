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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_schedule_id')->constrained('event_schedules')->onDelete('cascade');
            $table->string('event_name')->nullable(); // event title
            $table->string('title')->nullable(); // post test and pre test
            $table->integer('passing_score')->nullable();
            $table->timestamps();
        });

        Schema::create('assessment_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('assessments')->onDelete('cascade');
            $table->string('question')->nullable(); // event title
            $table->string('question_type')->nullable(); // post test and pre test
            $table->integer('points')->nullable();
            $table->integer('sort_order')->nullable();
            $table->timestamps();
        });

        Schema::create('assessment_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_question_id')->constrained('assessment_questions')->onDelete('cascade');
            $table->string('option_text')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->timestamps();
        });


        Schema::create('assessment_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('assessment_question_id')
                ->constrained('assessment_questions')
                ->cascadeOnDelete();
            $table->foreignId('assessment_option_id')
                ->nullable()
                ->constrained('assessment_options')
                ->noActionOnDelete();   // was nullOnDelete()

            $table->foreignId('nominated_employee_id')
                ->constrained('nominated_employees')
                ->noActionOnDelete();

            $table->text('answer_text')->nullable();

            $table->boolean('is_correct')->nullable();

            $table->integer('earned_points')->default(0);

            $table->timestamps();

            $table->unique([
                'assessment_question_id',
                'nominated_employee_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_answers');
        Schema::dropIfExists('assessment_options');
        Schema::dropIfExists('assessment_questions');
        Schema::dropIfExists('assessments');
    }
};
