
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('learning_application_plan_forms', function (Blueprint $table) {
            $table->text('image')->nullable();
        });

        Schema::table('learning_application_monitoring_forms', function (Blueprint $table) {
            $table->text('image')->nullable();
        });

        Schema::table('learning_implementation_forms', function (Blueprint $table) {
            $table->text('image')->nullable();
        });

        Schema::table('training_evaluation_speakers', function (Blueprint $table) {
            $table->text('image')->nullable();
        });

        Schema::table('evaluation_instruments', function (Blueprint $table) {
            $table->text('image')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('learning_application_plan_forms', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('learning_application_monitoring_forms', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('learning_implementation_forms', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('training_evaluation_speakers', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('evaluation_instruments', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
