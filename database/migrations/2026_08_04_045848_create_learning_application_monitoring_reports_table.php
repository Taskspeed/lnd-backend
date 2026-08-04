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
        Schema::create('learning_application_monitoring_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('forms_name')->nullable();
            $table->string('control_no')->nullable();
            $table->string('learner')->nullable();
            $table->string('lnd_attended')->nullable();
            $table->string('date_of_attendance')->nullable();
            $table->string('competency_developed_acquired')->nullable();
            $table->text('goals')->nullable();
            $table->text('performance_indicator')->nullable();
            $table->text('learning_strategies_applied')->nullable();
            $table->text('required_resources')->nullable();
            $table->text('target_date_completion')->nullable();
            $table->text('status_as_of_v1')->nullable();
            $table->text('status_as_of_v2')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

            Schema::create('core_monitoring', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_application_monitoring_report_id')->constrained('learning_application_monitoring_reports')->onDelete('cascade');
            $table->boolean('delivering_service_excellence')->default(false);
            $table->boolean('exemplifying_integrity')->default(false);
            $table->boolean('interpersonal_skills')->default(false);
            $table->timestamps();
        });

           Schema::create('technical_monitoring', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_application_monitoring_report_id')->constrained('learning_application_monitoring_reports')->onDelete('cascade');
            $table->boolean('planning_organizing')->default(false);
            $table->boolean('monitoring_evaluation')->default(false);
            $table->boolean('records_management')->default(false);
            $table->boolean('partnering_networking')->default(false);
            $table->boolean('process_management')->default(false);
            $table->timestamps();
        });

            Schema::create('leadership_monitoring', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_application_monitoring_report_id')->constrained('learning_application_monitoring_reports')->onDelete('cascade');
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
        Schema::dropIfExists(['learning_application_monitoring_reports', 'core_monitoring', 'technical_monitoring', 'leadership_monitoring']);
    }
};
