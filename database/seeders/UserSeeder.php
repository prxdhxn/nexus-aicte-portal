<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Create 5 mock institutes
        for ($i = 0; $i < 5; $i++) {
            User::create([
                'name' => $faker->company() . ' Institute of Technology',
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('password123'),
                'role' => 'institute',
            ]);
        }
    }
}
