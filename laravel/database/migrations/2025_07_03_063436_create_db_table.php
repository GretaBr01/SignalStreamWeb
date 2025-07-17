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
            $table->text('note')->nullable();            
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
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

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable(); // percorso immagine
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
            $table->foreign('category_id')->references('id')->on('categories');

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
        Schema::dropIfExists('categories');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['age', 'gender', 'sport', 'training_duration']);
        });
    }
};
