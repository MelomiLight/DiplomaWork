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
        Schema::create('running_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->float('distance_km')->nullable(); // distance covered
            $table->timestamp('start_time')->nullable(); // start of the session
            $table->timestamp('end_time')->nullable(); // end of the session
            $table->time('total_time')->nullable();
            $table->float('average_speed')->nullable(); // average speed
            $table->float('max_speed')->nullable(); // maximum speed
            $table->float('calories_burned')->nullable(); // kilocalories burned
            $table->integer('points')->nullable(); // points earned
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('running_sessions');
    }
};
