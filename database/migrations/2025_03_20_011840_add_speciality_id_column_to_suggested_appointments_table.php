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
        Schema::table('suggested_appointments', function (Blueprint $table) {
            $table->unsignedBigInteger('speciality_id')->nullable();
            $table->foreign('speciality_id')->references('id')->on('specialities')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suggested_appointments', function (Blueprint $table) {
            //
        });
    }
};
