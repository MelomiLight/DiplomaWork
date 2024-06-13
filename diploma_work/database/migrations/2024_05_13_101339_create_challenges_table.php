<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('due_type', 50)->nullable(); // 'daily', 'weekly', 'monthly'
            $table->string('challenge_type')->nullable();// 'distanceChallenge'
            $table->boolean('is_active')->default(false);
            $table->integer('points')->nullable();
            $table->float('distance_km')->nullable(); // required distance in km
            $table->time('time')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};
