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
            $table->bigInteger('timestamp');
            $table->integer('emg0');
            $table->integer('emg1');
            $table->integer('emg2');
            $table->integer('emg3');
            $table->unsignedBigInteger('series_id');
            $table->timestamps();
        });


        Schema::create('imu_samples', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('timestamp');
            $table->float('gyr_x');
            $table->float('gyr_y');
            $table->float('gyr_z');
            $table->float('acc_x');
            $table->float('acc_y');
            $table->float('acc_z');
            $table->unsignedBigInteger('series_id');
            $table->timestamps();
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
    }
};
