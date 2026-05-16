<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Curriculum;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class CurriculumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Ensure we have at least one SME to assign curricula to
        $sme = User::where('role', 'sme')->first();
        if (!$sme) {
            $sme = User::create([
                'name' => 'Dr. ' . $faker->lastName(),
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('password123'),
                'role' => 'sme',
            ]);
        }

        $topics = ['Computer Science', 'Mechanical Engineering', 'Artificial Intelligence', 'Data Science', 'Electronics & Communication'];

        // Create 10 mock curricula
        for ($i = 0; $i < 10; $i++) {
            $title = $faker->randomElement($topics) . ' - ' . $faker->catchPhrase();
            
            Curriculum::create([
                'title' => $title,
                'description' => $faker->paragraphs(3, true),
                'deadline' => $faker->dateTimeBetween('now', '+2 months'),
                'sme_id' => $sme->id,
                'tags' => $faker->randomElements(['AI/ML', 'Web Dev', 'Data Science', 'Cybersecurity', 'Cloud', 'IoT', 'Blockchain', 'Robotics'], rand(2, 4)),
            ]);
        }
    }
}
