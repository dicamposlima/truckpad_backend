<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDrivers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->nullable(false);
            $table->string('phone', 50)->nullable();
            $table->string('date_of_birth')->nullable(false);
            $table->string('cnh', 11)->nullable(false);
            $table->string('cpf', 11)->nullable(false);
            $table->enum('gender', ['M', 'F', 'ND'])->nullable(false)->comment('F=Feminine, M=Male, ND=Not declared');
            $table->unsignedTinyInteger('has_vehicles')->nullable(false)->comment('1= Yes, 0= No');
            $table->unsignedTinyInteger('active')->nullable(false)->default(1)->comment('1= Yes, 0= No');
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
        Schema::dropIfExists('drivers');

    }
}
