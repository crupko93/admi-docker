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

        $user = User::create([
            'username'   => 'admin',
            'first_name' => 'Admin',
            'last_name'  => 'Test',
            'email'      => 'cristian.serban@flygogroup.com',
            'password'   => bcrypt('admin')
        ]);
        $user->assignRole('admin');

        $user = User::create([
            'username'   => 'moderator',
            'first_name' => 'Moderator',
            'last_name'  => 'Test',
            'email'      => 'test@email.com',
            'password'   => bcrypt('moderator')
        ]);
        $user->assignRole('moderator');

        $user = User::create([
            'username'   => 'user',
            'first_name' => 'User',
            'last_name'  => 'Test',
            'email'      => 'test@email.com',
            'password'   => bcrypt('user')
        ]);
        $user->assignRole('user');

        for ($i = 0; $i < 7; $i++) {
            $user = User::create([
                'username'   => $faker->userName,
                'first_name' => $faker->firstName,
                'last_name'  => $faker->lastName,
                'email'      => $faker->email,
                'password'   => Hash::make($faker->randomNumber())
            ]);
            $user->assignRole('admin');
        }

        for ($x = 0; $x < 14; $x++) {
            $user = User::create([
                'username'   => $faker->userName,
                'first_name' => $faker->firstName,
                'last_name'  => $faker->lastName,
                'email'      => $faker->email,
                'password'   => Hash::make($faker->randomNumber())
            ]);
            $user->assignRole('moderator');
        }

        for ($z = 0; $z < 30; $z++) {
            $user = User::create([
                'username'   => $faker->userName,
                'first_name' => $faker->firstName,
                'last_name'  => $faker->lastName,
                'email'      => $faker->email,
                'password'   => Hash::make($faker->randomNumber())
            ]);
            $user->assignRole('user');
        }
    }
}
