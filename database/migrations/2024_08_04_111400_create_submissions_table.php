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
        Schema::create("submissions", function (Blueprint $table) {
            $table->id();
            $table->string("email");
            $table->integer("calories");
            $table->integer("protein");
            $table->integer("current_bodyfat");
            $table->integer("goal_bodyfat");
            $table->foreignId("guest_id")->constrained()->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("submissions");
    }
};
