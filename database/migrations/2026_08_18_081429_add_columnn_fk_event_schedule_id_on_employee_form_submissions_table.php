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
        Schema::table('employee_form_submissions', function (Blueprint $table) {
            $table->foreignId('event_schedule_id')
                ->constrained('event_schedules')
                ->onDelete('no action')
                ->after('event_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_form_submissions', function (Blueprint $table) {
            //
             $table->dropConstrainedForeignId('event_schedule_id');
        });
    }
};
