<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Driver;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Driver::class, function (Faker $faker) {
    return [
        "name" => "Antonio Carlos",
        "cpf" => 11111111111,
        "cnh" => 11111111111,
        "date_of_birth" => "1238599005453",
        "gender" => "M",
        "has_vehicles" => 1,
        "cnh_type" => "D"
    ];
});
