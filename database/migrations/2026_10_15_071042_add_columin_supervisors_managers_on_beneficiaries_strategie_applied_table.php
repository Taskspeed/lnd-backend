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
        Schema::table('beneficiaries_strategie_applied', function (Blueprint $table) {
            //

            $table->boolean('supervisors_managers')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficiaries_strategie_applied', function (Blueprint $table) {
            //
            $table->dropColumn('supervisors_managers');
        });
    }
};
