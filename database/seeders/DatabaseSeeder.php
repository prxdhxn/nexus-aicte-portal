<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'password' => bcrypt('password'), 'role' => 'admin']
        );

        // Create SMEs
        $sme1 = User::firstOrCreate(
            ['email' => 'sme@example.com'],
            ['name' => 'Dr. Sharma (SME)', 'password' => bcrypt('password'), 'role' => 'sme']
        );
        $sme2 = User::firstOrCreate(
            ['email' => 'sme2@example.com'],
            ['name' => 'Prof. Gupta (SME)', 'password' => bcrypt('password'), 'role' => 'sme']
        );

        // Create Institutes
        $institute1 = User::firstOrCreate(
            ['email' => 'institute@example.com'],
            ['name' => 'Tech Institute', 'password' => bcrypt('password'), 'role' => 'institute']
        );
        $institute2 = User::firstOrCreate(
            ['email' => 'institute2@example.com'],
            ['name' => 'Engineering College', 'password' => bcrypt('password'), 'role' => 'institute']
        );

        // Create Curricula
        $c1 = \App\Models\Curriculum::firstOrCreate(
            ['title' => 'AI & Data Science B.Tech Model Curriculum'],
            [
                'description' => 'A comprehensive model curriculum for B.Tech in Artificial Intelligence and Data Science. Includes core AI subjects, ML labs, and capstone projects.',
                'deadline' => now()->addDays(5)->format('Y-m-d'),
                'sme_id' => $sme1->id,
            ]
        );

        $c2 = \App\Models\Curriculum::firstOrCreate(
            ['title' => 'Cybersecurity & Forensics Diploma Curriculum'],
            [
                'description' => 'Standardized curriculum for diploma in Cybersecurity. Focuses on ethical hacking, network security, and incident response.',
                'deadline' => now()->subDays(2)->format('Y-m-d'),
                'sme_id' => $sme2->id,
            ]
        );

        $c3 = \App\Models\Curriculum::firstOrCreate(
            ['title' => 'Quantum Computing Elective Syllabus'],
            [
                'description' => 'Elective syllabus for 4th-year engineering students covering quantum mechanics, quantum logic gates, and basic quantum algorithms.',
                'deadline' => now()->addDays(15)->format('Y-m-d'),
                'sme_id' => $sme1->id,
            ]
        );

        // Create Adoptions
        \App\Models\Adoption::firstOrCreate(
            ['user_id' => $institute1->id, 'curriculum_id' => $c1->id],
            [
                'file_path' => 'dummy.pdf',
                'approval_score' => 85,
                'feedback' => 'Good coverage of topics. Please ensure lab infrastructure is adequate.',
            ]
        );

        \App\Models\Adoption::firstOrCreate(
            ['user_id' => $institute2->id, 'curriculum_id' => $c1->id],
            [
                'file_path' => 'dummy.pdf',
                'approval_score' => null,
                'feedback' => null,
            ]
        );

        \App\Models\Adoption::firstOrCreate(
            ['user_id' => $institute1->id, 'curriculum_id' => $c2->id],
            [
                'file_path' => 'dummy.pdf',
                'approval_score' => 92,
                'feedback' => 'Excellent adoption plan.',
            ]
        );

        // Create Activity Logs
        \App\Models\ActivityLog::firstOrCreate(['description' => 'Created AI & Data Science Curriculum', 'user_id' => $sme1->id], ['action' => 'created']);
        \App\Models\ActivityLog::firstOrCreate(['description' => 'Submitted Adoption for AI & Data Science Curriculum', 'user_id' => $institute1->id], ['action' => 'submitted']);
        \App\Models\ActivityLog::firstOrCreate(['description' => 'Graded Adoption for AI & Data Science Curriculum', 'user_id' => $sme1->id], ['action' => 'graded']);

        // Call Faker Seeders
        $this->call([
            UserSeeder::class,
            CurriculumSeeder::class,
        ]);
    }
}
