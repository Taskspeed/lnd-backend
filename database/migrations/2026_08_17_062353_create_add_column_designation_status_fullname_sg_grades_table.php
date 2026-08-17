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
            $table->string('full_name')->nullable()->after('control_no');
            $table->string('designation')->nullable()->after('full_name');
            $table->string('status')->nullable()->after('designation');
            $table->string('sg')->nullable()->after('status');
            $table->string('level')->nullable()->after('sg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nominated_employees', function (Blueprint $table) {
            //

            $table->dropColumn(['full_name','designation','status','sg','level']);
        });
    }
};
