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
        Schema::create('event_core_competencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_schedule_id')->constrained('event_schedules')->onDelete('cascade');
            $table->boolean('delivering_service_excellence')->default(false);
            $table->boolean('exemplifying_integrity')->default(false);
            $table->boolean('interpersonal_skills')->default(false);
            $table->timestamps();
        });

           Schema::create('event_technical_competencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_schedule_id')->constrained('event_schedules')->onDelete('cascade');
            $table->boolean('planning_organizing')->default(false);
            $table->boolean('monitoring_evaluation')->default(false);
            $table->boolean('records_management')->default(false);
            $table->boolean('partnering_networking')->default(false);
            $table->boolean('process_management')->default(false);
            $table->boolean('attention_details')->default(false);
            $table->timestamps();
        });

            Schema::create('event_leadership_competencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_schedule_id')->constrained('event_schedules')->onDelete('cascade');
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
        Schema::dropIfExists('event_core_competencies');
        Schema::dropIfExists('event_technical_competencies');
        Schema::dropIfExists('event_leadership_competencies');
    }
};
