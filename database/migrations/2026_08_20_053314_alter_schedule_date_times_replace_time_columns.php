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
        Schema::table('schedule_date_times', function (Blueprint $table) {
            $table->dropColumn(['morning_in', 'morning_out', 'afternoon_in', 'afternoon_out']);
        });

        Schema::table('schedule_date_times', function (Blueprint $table) {
            $table->time('time_in')->nullable()->after('schedule_date');
            $table->time('time_out')->nullable()->after('time_in');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule_date_times', function (Blueprint $table) {
            $table->dropColumn(['time_in', 'time_out']);
        });

        Schema::table('schedule_date_times', function (Blueprint $table) {
            $table->time('morning_in')->nullable();
            $table->time('morning_out')->nullable();
            $table->time('afternoon_in')->nullable();
            $table->time('afternoon_out')->nullable();
        });
    }
};
