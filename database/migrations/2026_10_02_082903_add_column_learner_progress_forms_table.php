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
        Schema::table('learner_progress_forms', function (Blueprint $table) {
            //
            $table->integer('monitoring_evaluation_competency')->nullable();
            $table->integer('attention_detail_competency')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learner_progress_forms', function (Blueprint $table) {
            //
            $table->dropColumn(['monitoring_evaluation_competency','attention_detail_competency']);
        });
    }
};
