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
        Schema::create('learner_progress_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('form_name')->nullable();
            $table->string('control_no')->nullable();
            $table->string('learner')->nullable();
            $table->string('lnd_attended')->nullable();
            $table->string('date_of_attendance')->nullable();

            $table->integer('delivering_service_excellence_competency')->nullable();
            $table->integer('exemplifying_integrity_competency')->nullable();
            $table->integer('interpersonal_skills_competency')->nullable();
            $table->integer('planning_organizing_competency')->nullable();
            $table->integer('records_management_competency')->nullable();
            $table->integer('partnering_networking_competency')->nullable();
            $table->integer('process_management_competency')->nullable();
            $table->integer('managing_performance_coaching_results_competency')->nullable();
            $table->integer('building_collaborative_inclusive_working_relationships_competency')->nullable();
            $table->integer('thinking_strategically_creatively_competency')->nullable();
            $table->integer('problem_solving_decision_making_competency')->nullable();

            $table->text('remarks')->nullable();
            $table->timestamps();
        });

           Schema::create('core_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learner_progress_form_id')->constrained('learner_progress_forms')->onDelete('cascade');
            $table->boolean('delivering_service_excellence')->default(false);
            $table->boolean('exemplifying_integrity')->default(false);
            $table->boolean('interpersonal_skills')->default(false);
            $table->timestamps();
        });

           Schema::create('technical_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learner_progress_form_id')->constrained('learner_progress_forms')->onDelete('cascade');
            $table->boolean('planning_organizing')->default(false);
            $table->boolean('monitoring_evaluation')->default(false);
            $table->boolean('records_management')->default(false);
            $table->boolean('partnering_networking')->default(false);
            $table->boolean('process_management')->default(false);
            $table->timestamps();
        });

            Schema::create('leadership_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learner_progress_form_id')->constrained('learner_progress_forms')->onDelete('cascade');
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
    Schema::dropIfExists('leadership_progress');
    Schema::dropIfExists('technical_progress');
    Schema::dropIfExists('core_progress');
    Schema::dropIfExists('learner_progress_forms');   
     }
};
