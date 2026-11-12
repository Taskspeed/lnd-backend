
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // A. Personal Information
        Schema::create('speakers', function (Blueprint $table) {
            $table->id();

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->string('gender')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('email')->nullable();
            $table->string('contact_number')->nullable();
            $table->text('profile_photo')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        // B. Employment Information
        Schema::create('speaker_employment', function (Blueprint $table) {
            $table->id();

            $table->foreignId('speaker_id')
                ->unique()
                ->constrained('speakers')
                ->cascadeOnDelete();

            $table->string('position')->nullable();
            $table->string('agency')->nullable();
            $table->string('office')->nullable();
            $table->string('employment_type')->nullable();
            $table->unsignedTinyInteger('salary_grade')->nullable();

            $table->timestamps();
        });

        // C. Professional Information
        Schema::create('speaker_professional_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('speaker_id')->unique()->constrained('speakers')->cascadeOnDelete();
            $table->string('educational_attainment')->nullable();
            $table->string('professional_title')->nullable();
            $table->text('specialization')->nullable();
            $table->text('biography')->nullable();
            $table->unsignedSmallInteger('years_of_experience')->nullable();
            $table->string('resume_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('speaker_professional_info');
        Schema::dropIfExists('speaker_employment');
        Schema::dropIfExists('speakers');
    }
};
