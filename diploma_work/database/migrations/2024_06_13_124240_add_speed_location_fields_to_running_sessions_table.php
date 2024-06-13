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
            $table->json('speeds');
            $table->json('locations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('running_sessions', function (Blueprint $table) {
            $table->dropColumn('speeds');
            $table->dropColumn('locations');
        });
    }
};
