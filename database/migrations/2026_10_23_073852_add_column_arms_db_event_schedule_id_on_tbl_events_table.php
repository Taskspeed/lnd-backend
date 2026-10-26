<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

  protected $connection = 'ARMS_DB';
  
    public function up(): void
        {
            Schema::connection('ARMS_DB')->table('tblevents', function (Blueprint $table) {
                $table->unsignedBigInteger('event_schedule_id')->nullable();
            });
        }

        public function down(): void
        {
            Schema::connection('ARMS_DB')->table('tblevents', function (Blueprint $table) {
                $table->dropColumn('event_schedule_id');
            });
        }
};
