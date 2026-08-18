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
        Schema::create('learning_application_plan_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_form_submission_id')->constrained('employee_form_submissions')->onDelete('cascade');
            $table->string('form_name')->nullable();
            $table->string('control_no')->nullable();
            $table->string('office')->nullable();
            $table->string('learner')->nullable();
            $table->string('title_of_intervention')->nullable();
            $table->string('date_conducted')->nullable();
            $table->string('venue')->nullable();
            $table->boolean('foundation')->default(false);
            $table->boolean('techinal')->default(false);
            $table->boolean('supervisory')->default(false);
            $table->boolean('managerial')->default(false);
            $table->text('significant_learning_insight')->nullable();
            $table->timestamps();
        });

        Schema::create('foundation_competencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_application_plan_form_id')->constrained('learning_application_plan_forms')->onDelete('cascade');
            $table->boolean('delivering_service_excellence')->default(false);
            $table->boolean('exemplifying_integrity')->default(false);
            $table->boolean('interpersonal_skills')->default(false);
            $table->timestamps();
        });

        Schema::create('technical_competencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_application_plan_form_id')->constrained('learning_application_plan_forms')->onDelete('cascade');
            $table->boolean('planning_organizing')->default(false);
            $table->boolean('monitoring_evaluation')->default(false);
            $table->boolean('records_management')->default(false);
            $table->boolean('partnering_networking')->default(false);
            $table->boolean('process_management')->default(false);
            $table->boolean('attention_detail')->default(false);
            $table->timestamps();
        });

        Schema::create('managerial_competencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_application_plan_form_id')->constrained('learning_application_plan_forms')->onDelete('cascade');
            $table->boolean('managing_performance_coaching_results')->default(false);
            $table->boolean('building_collaborative_inclusive_working_relationships')->default(false);
            $table->boolean('thinking_strategically_creatively')->default(false);
            $table->boolean('problem_solving_decision_making')->default(false);
            $table->timestamps();
        });


        Schema::create('supervisory_competencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_application_plan_form_id')->constrained('learning_application_plan_forms')->onDelete('cascade');
            $table->boolean('supervisory_managing_performance_coaching_results')->default(false);
            $table->boolean('supervisory_building_collaborative_inclusive_working_relationships')->default(false);
            $table->timestamps();
        });

        Schema::create('learning_strategies_implemented', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_application_plan_form_id')->constrained('learning_application_plan_forms')->onDelete('cascade');
            $table->boolean('immediate_application_skills')->default(false);
            $table->boolean('knowledge_sharing')->default(false);
            $table->boolean('peer_coaching_collaboration')->default(false);
            $table->boolean('develop_office_policies_guidelines')->default(false);
            $table->boolean('create_pilot_project')->default(false);
            $table->boolean('include_ipcr')->default(false);
            $table->timestamps();
        });

        Schema::create('performance_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_application_plan_form_id')->constrained('learning_application_plan_forms')->onDelete('cascade');
            $table->boolean('strategic_functions')->default(false);
            $table->boolean('core_functions')->default(false);
            $table->boolean('support_functions')->default(false);
            $table->timestamps();
        });

        Schema::create('beneficiaries_strategie_applied', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_application_plan_form_id')->constrained('learning_application_plan_forms')->onDelete('cascade');
            $table->boolean('employees_staff')->default(false);
            $table->boolean('office_department')->default(false);
            $table->boolean('city_government_organization')->default(false);
            $table->boolean('clients_stakeholders_general_public')->default(false);
            $table->timestamps();
        });

        Schema::create('resources_utilized', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_application_plan_form_id')->constrained('learning_application_plan_forms')->onDelete('cascade');
            $table->boolean('digital_technologies')->default(false);
            $table->boolean('physical_printed_resources')->default(false);
            $table->boolean('human_resources_organizational_support')->default(false);
            $table->boolean('financial_logistical_support')->default(false);
            $table->boolean('policy_process_resources')->default(false);
            $table->timestamps();
        });

        Schema::create('target_date_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_application_plan_form_id')->constrained('learning_application_plan_forms')->onDelete('cascade');
            $table->boolean('within_2_weeks_after_training')->default(false);
            $table->boolean('within_1_month_after_training')->default(false);
            $table->boolean('within_2_months_after_training')->default(false);
            $table->boolean('within_3_months_after_training')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
   Schema::dropIfExists('target_date_completions');
    Schema::dropIfExists('resources_utilized');
    Schema::dropIfExists('beneficiaries_strategie_applied');
    Schema::dropIfExists('performance_indicators');
    Schema::dropIfExists('learning_strategies_implemented');
    Schema::dropIfExists('supervisory_competencies');
    Schema::dropIfExists('managerial_competencies');
    Schema::dropIfExists('technical_competencies');
    Schema::dropIfExists('foundation_competencies');
    Schema::dropIfExists('learning_application_plan_forms');

    }
};
