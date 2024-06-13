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
            $table->decimal('distance_km')->change();
            $table->timestamp('start_time')->nullable(); // start of the session
            $table->timestamp('end_time')->nullable(); // end of the session
            $table->time('total_time')->nullable();
            $table->decimal('average_speed')->change();
            $table->decimal('max_speed')->change();
            $table->decimal('calories_burned')->change();
            $table->integer('points')->nullable(); // points earned
            $table->json('speeds')->nullable();
            $table->json('locations')->nullable();
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
