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
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained()->cascadeOnDelete();
            $table->string('label');
            $table->string('name');
            $table->enum('type', ['text', 'email', 'number', 'textarea', 'select', 'checkbox'])->default('text');
            $table->json('options')->nullable(); // For manual select options
            $table->string('validation')->nullable();
            $table->boolean('required')->default(false);
            $table->integer('order')->default(0);
            $table->string('data_source')->nullable()->comment('tagging, brand, usecases');
            $table->text('checkbox_terms')->nullable();
            $table->timestamps();
            $table->index(['form_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};
