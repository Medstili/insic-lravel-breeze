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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->foreignId('speciality_id')->nullable();
            $table->foreign('speciality_id')->references('id')->on('specialities');
            $table->string('role')->default('coach');
            $table->boolean('is_available')->default(true);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_speciality_id_foreign');
            // $table->dropColumn('phone');
            $table->dropColumn('speciality_id');
            $table->dropColumn('is_admin');
            $table->dropColumn('role');
            $table->dropColumn('is_available');
        
            // $table->dropColumn('phone');
            // $table->dropColumn('speciality_id');
            // $table->dropColumn('is_admin');
            // $table->dropColumn('is_available');
            
        });
    }
};
