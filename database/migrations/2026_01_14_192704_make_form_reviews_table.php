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
        Schema::create('form_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_submission_id')->constrained('form_submissions')->cascadeOnDelete();
            $table->integer('rating')->nullable()->comment('1-5 rating (1=😡, 5=😄)');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_reviews', function (Blueprint $table) {
            //
        });
    }
};
