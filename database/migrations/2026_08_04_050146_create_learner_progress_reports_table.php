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
        Schema::create('learner_progress_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('forms_name')->nullable();
            $table->string('control_no')->nullable();
            $table->string('learner')->nullable();
            $table->string('lnd_attended')->nullable();
            $table->string('date_of_attendance')->nullable();
            $table->integer('delivering_service_excellence')->nullable();
            $table->integer('exemplifying_integrity')->nullable();
            $table->integer('interpersonal_skills')->nullable();
            $table->integer('planning_organizing')->nullable();
            $table->integer('records_management')->nullable();
            $table->integer('partnering_networking')->nullable();
            $table->integer('process_management')->nullable();
            $table->integer('managing_performance_coaching_results')->nullable();
            $table->integer('building_collaborative_inclusive_working_relationships')->nullable();
            $table->integer('thinking_strategically_creatively')->nullable();
            $table->integer('problem_solving_decision_making')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

           Schema::create('core_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learner_progress_report_id')->constrained('learner_progress_reports')->onDelete('cascade');
            $table->boolean('delivering_service_excellence')->default(false);
            $table->boolean('exemplifying_integrity')->default(false);
            $table->boolean('interpersonal_skills')->default(false);
            $table->timestamps();
        });

           Schema::create('technical_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learner_progress_report_id')->constrained('learner_progress_reports')->onDelete('cascade');
            $table->boolean('planning_organizing')->default(false);
            $table->boolean('monitoring_evaluation')->default(false);
            $table->boolean('records_management')->default(false);
            $table->boolean('partnering_networking')->default(false);
            $table->boolean('process_management')->default(false);
            $table->timestamps();
        });

            Schema::create('leadership_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learner_progress_report_id')->constrained('learner_progress_reports')->onDelete('cascade');
            $table->boolean('managing_performance_coaching_results')->default(false);
            $table->boolean('building_collaborative_inclusive_working_relationships')->default(false);
            $table->boolean('thinking_strategically_creatively')->default(false);
            $table->boolean('problem_solving_decision_making')->default(false);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(['learner_progress_reports', 'core_progress', 'technical_progress', 'leadership_progress']);
    }
};
