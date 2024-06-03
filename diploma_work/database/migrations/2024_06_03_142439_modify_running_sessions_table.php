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
        Schema::table('running_sessions', function (Blueprint $table) {
            $table->decimal('distance_km')->change();
            $table->decimal('average_speed')->change();
            $table->decimal('max_speed')->change();
            $table->decimal('calories_burned')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('running_sessions', function (Blueprint $table) {
            $table->float('distance_km')->change();
            $table->float('average_speed')->change();
            $table->float('max_speed')->change();
            $table->float('calories_burned')->change();
        });
    }
};
