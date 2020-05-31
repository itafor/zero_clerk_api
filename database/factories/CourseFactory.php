<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Course;
use Faker\Generator as Faker;

$factory->define(Course::class, function (Faker $faker) {
    return [
        'course_title' => $faker->unique()->lexify('????????'), 
        'course_code' => $faker->unique()->numerify('ABC###'),
        'credit_unit' => $faker->randomFloat(1, 1, 10), 
    ];
});
