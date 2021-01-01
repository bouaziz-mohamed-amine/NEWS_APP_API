<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Comment::class, function (Faker $faker) {
    return [
        'content'=> $faker->text,
        'data_written'=> now(),
        'user_id'=> $faker->numberBetween( 1 , 15),
        'post_id'=> $faker->numberBetween( 1 , 500 ),
    ];
});
