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

        Schema::create('series', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('ended_at')->nullable();
            $table->text('note')->nullable();            
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });

        Schema::create('emg_samples', function (Blueprint $table) {
            $table->id();
            $table->string('path')->nullable();
            $table->unsignedBigInteger('series_id');
            $table->timestamps();
        });


        Schema::create('imu_samples', function (Blueprint $table) {
            $table->id();
            $table->string('path')->nullable();
            $table->unsignedBigInteger('series_id');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('age')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('sport')->nullable();
            $table->string('training_duration')->nullable();
        });


        Schema::table('series', function(Blueprint $table){
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('emg_samples', function(Blueprint $table){
            $table->foreign('series_id')->references('id')->on('series');
        });

        Schema::table('imu_samples', function(Blueprint $table){
            $table->foreign('series_id')->references('id')->on('series');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imu_samples');
        Schema::dropIfExists('emg_samples');
        Schema::dropIfExists('series');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['age', 'gender', 'sport', 'training_duration']);
        });
    }
};
