<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Track;
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

$factory->define(Track::class, function (Faker $faker) {
    return [
        "latitude" => "-23.5601802",
        "longitude" => "-46.6415725,15",
        "on_way" => 0,
        "has_truckload" => 0,
        "driver_id" => 1,
        "type_id" => 1,
    ];
});
