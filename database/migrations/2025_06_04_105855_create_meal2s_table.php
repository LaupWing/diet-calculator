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

            $table->string('day');           // e.g., day1, day2, etc.
            $table->string('meal_type');     // e.g., breakfast, lunch, etc.

            $table->string('name');
            $table->integer('calories');
            $table->integer('protein');
            $table->integer('carbs');
            $table->integer('fats');

            $table->text('description');

            $table->json('ingredients');
            $table->json('instructions'); // array of objects with title + description
            $table->json('serving_suggestions');

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
