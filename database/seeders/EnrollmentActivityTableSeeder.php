<?php

namespace Database\Seeders;
use App\EnrollmentActivity;
use Faker\Factory;
use Illuminate\Database\Seeder;

class EnrollmentActivityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's clear the users table first
        EnrollmentActivity::truncate();
        $faker = Factory::create();

        // And now let's generate a few dozen users for our app:
        for ($i = 0; $i < 10; $i++) {
            EnrollmentActivity::create([
                'course_id' => $faker->numberBetween(20,1000),
                'course_name' => $faker->name,
                'active_users_count' => $faker->numberBetween(20,72000),
                'activity_date' => $faker->dateTime()
            ]);
        }
    }
}
