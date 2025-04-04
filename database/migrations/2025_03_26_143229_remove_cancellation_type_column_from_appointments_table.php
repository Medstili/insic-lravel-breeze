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
        Schema::table('Appointments', function (Blueprint $table) {
            $table->dropColumn('cancellation_type');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Appointments', function (Blueprint $table) {
            $table->string('cancellation_type')->nullable();
        });
    }
};
