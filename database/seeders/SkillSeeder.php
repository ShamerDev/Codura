<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            'UI/UX', 'HTML', 'CSS', 'JavaScript', 'React', 'Tailwind', 'Vue', // Front-End
            'PHP', 'Laravel', 'Node.js', 'Python', 'Authentication', 'APIs', 'MVC', // Back-End
            'MySQL', 'PostgreSQL', 'Firebase', 'MongoDB', 'ETL', 'ORM', // Data
            'Git', 'GitHub', 'Docker', 'CI/CD', 'Linux', 'Vercel', 'AWS', // DevOps
            'Communication', 'Teamwork', 'Time Management', 'Problem Solving', 'Leadership' // Soft Skills
        ];

        foreach ($skills as $name) {
            Skill::create(['name' => $name]);
        }
    }
}
