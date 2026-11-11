<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::table('event_forms', function (Blueprint $table) {
            // Add nullable column first
            $table->unsignedBigInteger('event_schedule_id')->nullable();
        });

        // IMPORTANT:
        // Map existing event_id values to the correct event_schedule_id
        // before dropping event_id.
        
        Schema::table('event_forms', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');

            $table->foreign('event_schedule_id')
                ->references('id')
                ->on('event_schedules')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('event_forms', function (Blueprint $table) {
            $table->dropForeign(['event_schedule_id']);
            $table->dropColumn('event_schedule_id');

            $table->foreignId('event_id')
                ->nullable()
                ->constrained('events')
                ->cascadeOnDelete();
        });
    }
};
