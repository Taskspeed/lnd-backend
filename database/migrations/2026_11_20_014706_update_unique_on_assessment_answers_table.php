<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessment_answers', function (Blueprint $table) {
            // ang pangalang ito ay galing mismo sa error mo
            $table->dropUnique('assessment_answers_assessment_question_id_nominated_employee_id_unique');

            $table->unique(
                ['assessment_question_id', 'nominated_employee_id', 'assessment_option_id'],
                'assessment_answers_question_employee_option_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('assessment_answers', function (Blueprint $table) {
            $table->dropUnique('assessment_answers_question_employee_option_unique');

            $table->unique(
                ['assessment_question_id', 'nominated_employee_id'],
                'assessment_answers_assessment_question_id_nominated_employee_id_unique'
            );
        });
    }
};