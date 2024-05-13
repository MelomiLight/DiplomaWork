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
        Schema::create('run_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->float('daily_distance_km')->nullable();
            $table->timestamp('daily_time')->nullable();
            $table->float('daily_calories_burned')->nullable();
            $table->float('weekly_distance_km')->nullable();
            $table->timestamp('weekly_time')->nullable();
            $table->float('weekly_calories_burned')->nullable();
            $table->float('monthly_distance_km')->nullable();
            $table->timestamp('monthly_time')->nullable();
            $table->float('monthly_calories_burned')->nullable();
            $table->float('total_distance_km')->nullable();
            $table->timestamp('total_time')->nullable();
            $table->float('total_calories_burned')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('run_information');
    }
};
