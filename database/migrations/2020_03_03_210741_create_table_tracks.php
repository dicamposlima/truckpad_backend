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
            $table->string('name', 100)->nullable(false);
            $table->smallInteger('latitude')->nullable(false);
            $table->smallInteger('longitude')->nullable(false);
            $table->unsignedTinyInteger('on_way')->nullable(false)->default(1)->comment('1= Yes, 0= No');
            $table->unsignedTinyInteger('has_truckload')->nullable(false)->comment('1= Yes, 0= No');
            $table->unsignedBigInteger('truck_drivers_id')->nullable()->comment('Definition in truck_drivers table');
            $table->unsignedBigInteger('vehicles_type_id')->nullable()->comment('Definition in vehicles_types table');
            $table->foreign('truck_drivers_id')->references('id')->on('truck_drivers')->onDelete('set null');
            $table->foreign('vehicles_type_id')->references('id')->on('vehicles_types')->onDelete('set null');
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
