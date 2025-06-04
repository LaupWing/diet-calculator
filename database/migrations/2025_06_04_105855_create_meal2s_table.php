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
        Schema::create('meal2s', function (Blueprint $table) {
            $table->id();

            $table->integer('day');           // e.g., day1, day2, etc.
            $table->string('meal_type');     // e.g., breakfast, lunch, etc.

            $table->string('name');
            $table->integer('calories');
            $table->integer('protein');
            $table->integer('carbs')->nullable();
            $table->integer('fats')->nullable();

            $table->text('description')->nullable();

            $table->json('ingredients')->nullable();
            $table->json('instructions')->nullable(); // array of objects with title + description
            $table->json('serving_suggestions')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal2s');
    }
};
