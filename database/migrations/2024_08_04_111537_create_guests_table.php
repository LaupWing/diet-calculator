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
        Schema::create("guest", function (Blueprint $table) {
            $table->id();
            $table->string("age");
            $table->string("gender");
            $table->string("height");
            $table->string("weight");
            $table->string("activity");
            $table->string("goal_weight");
            $table->string("goal_months");
            $table->string("unit");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
