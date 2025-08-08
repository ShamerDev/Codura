<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SkillCategory;

class SkillCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Front-End Development',
            'Back-End Development',
            'Database & Data Handling',
            'DevOps & Deployment',
            'Professional & Soft Skills',
        ];

        foreach ($categories as $name) {
            SkillCategory::create(['name' => $name]);
        }
    }
}
