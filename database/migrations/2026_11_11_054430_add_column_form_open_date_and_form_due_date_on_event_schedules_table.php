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
        Schema::table('event_schedules', function (Blueprint $table) {
            //
            $table->dateTime('form_open_date')->nullable();
            $table->dateTime('form_due_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_schedules', function (Blueprint $table) {
            //
            $table->dropColumn(['form_open_date', 'form_due_date']);
        });
    }
};
