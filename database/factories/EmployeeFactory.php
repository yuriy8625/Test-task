<?php

/** @var Factory $factory */

use App\Models\Employee;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$positions = \App\Models\Position::all()->pluck('id');

$factory->define(Employee::class, function (Faker $faker) use ($positions) {

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'phone' => substr($faker->unique()->e164PhoneNumber, 0, -1),
        'photo' => 'photo.png',
        'position_id' => $positions[mt_rand(0, count($positions) - 1)],
        'salary' => rand(10000, 500000),
        'employment_at' => now(),
        'admin_created_id' => 1,
        'admin_updated_id' => 1,
    ];
});
