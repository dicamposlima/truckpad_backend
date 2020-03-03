<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTruckDrivers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('truck_drivers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->nullable(false);
            $table->unsignedTinyInteger('age')->nullable(false)->comment('>= 18 and <= 120');
            $table->enum('gender', ['M', 'F', 'ND'])->nullable(false)->comment('F=Feminine, M=Male, ND=Not declared');
            $table->unsignedTinyInteger('has_vehicles')->nullable(false)->comment('1= Yes, 0= No');
            $table->enum('cnh_type', ['A', 'B', 'C', 'D', 'E'])->nullable(false);
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
        Schema::dropIfExists('truck_drivers');

    }
}
