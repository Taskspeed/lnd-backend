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
        Schema::table('event_speakers', function (Blueprint $table) {

            // Remove existing foreign key
            $table->dropForeign(['event_schedule_id']);

            // Remove old column
            $table->dropColumn('event_schedule_id');

            // Create new foreign key column
            $table->foreignId('schedule_date_time_id')
                ->constrained('schedule_date_times')
                ->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_speakers', function (Blueprint $table) {

            // Remove new foreign key
            $table->dropForeign(['schedule_date_time_id']);

            // Remove new column
            $table->dropColumn('schedule_date_time_id');

            // Restore original foreign key column
            $table->foreignId('event_schedule_id')
                ->constrained('event_schedules')
                ->cascadeOnDelete();

        });
    }
};