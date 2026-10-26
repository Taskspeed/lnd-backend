<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('event_schedules', function (Blueprint $table) {
        $table->text('venue_name')->nullable()->change();
        $table->text('qualifications')->nullable()->change();
        // o kung may limit pa rin: $table->string('venue_name', 500)->change();
    });
}

public function down(): void
{
    Schema::table('event_schedules', function (Blueprint $table)
     {  $table->string('qualifications', 255)->nullable()->change();
        $table->string('venue_name', 255)->nullable()->change();
    });
}
};
