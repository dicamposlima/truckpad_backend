<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTracks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('latitude', 180)->nullable(false);
            $table->string('longitude', 180)->nullable(false);
            $table->unsignedTinyInteger('on_way')->nullable(false)->default(1)->comment('1= Yes, 0= No');
            $table->unsignedTinyInteger('has_truckload')->nullable(false)->comment('1= Yes, 0= No');
            $table->unsignedBigInteger('driver_id')->nullable()->comment('Definition in drivers table');
            $table->unsignedBigInteger('type_id')->nullable()->comment('Definition in types table');
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('set null');
            $table->foreign('type_id')->references('id')->on('types')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tracks');

    }
}
