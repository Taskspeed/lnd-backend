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
            $table->foreignId('speaker_id')
                ->nullable()
                ->constrained('speakers')
                ->noActionOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('event_speakers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('speaker_id');
        });
    }
};
