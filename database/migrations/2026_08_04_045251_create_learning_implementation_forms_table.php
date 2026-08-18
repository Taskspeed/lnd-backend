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
        Schema::create('learning_implementation_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_form_submission_id')->constrained('employee_form_submissions')->onDelete('cascade');
            $table->string('form_name')->nullable();
            $table->string('control_no')->nullable();
            $table->string('learner')->nullable();
            $table->string('lnd_attended')->nullable();
            $table->string('date_of_attendance')->nullable();
            $table->string('competency_developed_acquired')->nullable();
            $table->text('learning_strategies_applied')->nullable();
            $table->text('resources_used')->nullable();
            $table->text('beneficiaries_strategies_applied')->nullable();
            $table->text('performance_indicators_behavior_toward_work')->nullable();
            $table->text('financial_aid_training_attended')->nullable();
            $table->text('return_financial_aid')->nullable();
            $table->timestamps();
        });

         Schema::create('core_implementation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_implementation_form_id')->constrained('learning_implementation_forms')->onDelete('cascade');
            $table->boolean('delivering_service_excellence')->default(false);
            $table->boolean('exemplifying_integrity')->default(false);
            $table->boolean('interpersonal_skills')->default(false);
            $table->timestamps();
        });

           Schema::create('technical_implementation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_implementation_form_id')->constrained('learning_implementation_forms')->onDelete('cascade');
            $table->boolean('planning_organizing')->default(false);
            $table->boolean('monitoring_evaluation')->default(false);
            $table->boolean('records_management')->default(false);
            $table->boolean('partnering_networking')->default(false);
            $table->boolean('process_management')->default(false);
            $table->timestamps();
        });

            Schema::create('leadership_implementation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_implementation_form_id')->constrained('learning_implementation_forms')->onDelete('cascade');
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
       
        Schema::dropIfExists('core_implementation');
        Schema::dropIfExists('technical_implementation');
        Schema::dropIfExists('leadership_implementation');
        Schema::dropIfExists('learning_implementation_forms');

    }
};
