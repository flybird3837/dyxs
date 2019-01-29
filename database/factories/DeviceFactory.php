<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Device::class, function (Faker $faker) {
    return [
        'project_id' => 1,
        'uuid' => $faker->uuid,
        'name' => $faker->text(20)
    ];
});
