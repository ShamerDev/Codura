<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skill;
use App\Models\SkillCategory;
use App\Models\SkillCategoryLink;

class SkillCategoryLinkSeeder extends Seeder
{
    public function run(): void
    {
        $mapping = [
            'Front-End Development' => ['UI/UX', 'HTML', 'CSS', 'JavaScript', 'React', 'Tailwind', 'Vue'],
            'Back-End Development' => ['PHP', 'Laravel', 'Node.js', 'Python', 'Authentication', 'APIs', 'MVC'],
            'Database & Data Handling' => ['MySQL', 'PostgreSQL', 'Firebase', 'MongoDB', 'ETL', 'ORM'],
            'DevOps & Deployment' => ['Git', 'GitHub', 'Docker', 'CI/CD', 'Linux', 'Vercel', 'AWS'],
            'Professional & Soft Skills' => ['Communication', 'Teamwork', 'Time Management', 'Problem Solving', 'Leadership'],
        ];

        foreach ($mapping as $categoryName => $skills) {
            $category = SkillCategory::where('name', $categoryName)->first();

            foreach ($skills as $skillName) {
                $skill = Skill::where('name', $skillName)->first();

                if ($category && $skill) {
                    SkillCategoryLink::create([
                        'skill_id' => $skill->id,
                        'skill_category_id' => $category->id,
                    ]);
                }
            }
        }
    }
}
