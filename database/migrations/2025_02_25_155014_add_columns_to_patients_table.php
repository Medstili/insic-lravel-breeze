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
        Schema::table('patients', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('ecole')->nullable();
            $table->string('system')->nullable();
            $table->string('parent_first_name')->nullable(false);
            $table->string('parent_last_name')->nullable(false);
            $table->string('profession')->nullable(false);
            $table->string('etablissment')->nullable(false);
            $table->string('email')->unique()->nullable(false);
            $table->string('address')->nullable(false);
            $table->string('mode')->nullable(false);
            $table->string('subscription')->nullable(false);
            $table->unsignedBigInteger('speciality_id')->nullable(false);
            $table->foreign('speciality_id')->references('id')->on('specialities');
            
        });
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('ecole');
            $table->dropColumn('system');
            $table->dropColumn('parent_first_name');
            $table->dropColumn('parent_last_name');
            $table->dropColumn('profession');
            $table->dropColumn('etablissment');
            $table->dropColumn('email');
            $table->dropColumn('address');
            $table->dropColumn('mode');
            $table->dropColumn('subscription');
            $table->dropColumn('speciality');
        });
    }
};
