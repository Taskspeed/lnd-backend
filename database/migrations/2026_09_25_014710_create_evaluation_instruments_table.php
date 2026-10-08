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
        Schema::create('evaluation_instruments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_form_submission_id')->constrained('employee_form_submissions')->onDelete('cascade');
            $table->string('event')->nullable();
            $table->string('date')->nullable();
            $table->string('venue')->nullable();
            $table->string('name_of_participant')->nullable();
            $table->text('comments_suggestions')->nullable();
            $table->timestamps();
        });


        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_instrument_id')->constrained('evaluation_instruments')->onDelete('cascade');
            $table->string('question')->nullable();
            $table->boolean('strongly_agree')->default(false);
            $table->boolean('agree')->default(false);
            $table->boolean('disagree')->default(false);
            $table->boolean('strongly_disagree')->default(false);

            $table->timestamps();
        });


        Schema::create('facilitators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_instrument_id')->constrained('evaluation_instruments')->onDelete('cascade');
            $table->string('question')->nullable();
            $table->boolean('strongly_agree')->default(false);
            $table->boolean('agree')->default(false);
            $table->boolean('disagree')->default(false);
            $table->boolean('strongly_disagree')->default(false);

            $table->timestamps();
        });

        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_instrument_id')->constrained('evaluation_instruments')->onDelete('cascade');
            $table->string('question')->nullable();
            $table->boolean('strongly_agree')->default(false);
            $table->boolean('agree')->default(false);
            $table->boolean('disagree')->default(false);
            $table->boolean('strongly_disagree')->default(false);

            $table->timestamps();
        });

        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_instrument_id')->constrained('evaluation_instruments')->onDelete('cascade');
            $table->string('excellent')->nullable();
            $table->boolean('high_average')->default(false);
            $table->boolean('good')->default(false);
            $table->boolean('poor')->default(false);
            $table->boolean('fair')->default(false);
            $table->boolean('highly_recommended')->default(false);
            $table->boolean('not_a_priority')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_instruments');
        Schema::dropIfExists('topics');
        Schema::dropIfExists('facilitators');
        Schema::dropIfExists('venues');
        Schema::dropIfExists('feedbacks');
    }
};
