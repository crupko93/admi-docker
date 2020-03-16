<?php

use Illuminate\Database\Seeder;

use App\User;

use Faker\Factory;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        User::create([
            'username'   => 'admin',
            'first_name' => 'Admin',
            'last_name'  => 'Test',
            'email'      => 'cristian.serban@flygogroup.com',
            'password'   => bcrypt('admin'),
            'role'       => 'admin'
        ]);

        User::create([
            'username'   => 'moderator',
            'first_name' => 'Moderator',
            'last_name'  => 'Test',
            'email'      => 'test@email.com',
            'password'   => bcrypt('moderator'),
            'role'       => 'moderator'
        ]);

        User::create([
            'username'   => 'user',
            'first_name' => 'User',
            'last_name'  => 'Test',
            'email'      => 'test@email.com',
            'password'   => bcrypt('user'),
            'role'       => 'user'
        ]);

        for ($i = 0; $i < 7; $i++) {
            User::create([
                'username'   => $faker->userName,
                'first_name' => $faker->firstName,
                'last_name'  => $faker->lastName,
                'email'      => $faker->email,
                'password'   => Hash::make($faker->randomNumber()),
                'role'       => 'admin'
            ]);
        }

        for ($x = 0; $x < 14; $x++) {
            User::create([
                'username'   => $faker->userName,
                'first_name' => $faker->firstName,
                'last_name'  => $faker->lastName,
                'email'      => $faker->email,
                'password'   => Hash::make($faker->randomNumber()),
                'role'       => 'moderator'
            ]);
        }

        for ($z = 0; $z < 30; $z++) {
            User::create([
                'username'   => $faker->userName,
                'first_name' => $faker->firstName,
                'last_name'  => $faker->lastName,
                'email'      => $faker->email,
                'password'   => Hash::make($faker->randomNumber()),
                'role'       => 'user'
            ]);
        }
    }
}
