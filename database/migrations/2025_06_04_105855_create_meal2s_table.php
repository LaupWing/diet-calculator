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

            $table->string('day');
            $table->string('meal_type');
            $table->string('email');

            $table->string('name');
            $table->integer('calories');
            $table->integer('protein');
            $table->integer('carbs');
            $table->integer('fats');

            $table->text('description');

            $table->json('ingredients');
            $table->json('instructions');
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
