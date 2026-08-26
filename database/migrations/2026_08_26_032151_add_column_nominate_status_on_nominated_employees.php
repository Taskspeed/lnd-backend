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
        Schema::table('nominated_employees', function (Blueprint $table) {
            //
            $table->string('nominate_status')->nullable()->after('level');
            $table->string('nominate_reason')->nullable()->after('level');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nominated_employees', function (Blueprint $table) {
            //
            $table->dropColumn(['nominate_status','nominate_reason']);
        });
    }
};
