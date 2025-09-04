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
            'Front-End Development' => [
                'HTML', 'CSS', 'JavaScript', 'TypeScript', 'React', 'Vue.js', 'Angular',
                'Next.js', 'Nuxt.js', 'Tailwind CSS', 'Bootstrap', 'Sass/SCSS',
                'Responsive Design', 'UI/UX Design', 'Accessibility (a11y)', 'State Management',
                'Component Architecture', 'Single Page Applications (SPA)', 'Progressive Web Apps (PWA)',
                'Frontend Testing', 'React Native', 'Flutter', 'Figma',
            ],

            'Back-End Development' => [
                'PHP', 'Laravel', 'Symfony', 'Node.js', 'Express.js', 'Python', 'Django',
                'Flask', 'FastAPI', 'Java', 'Spring Boot', 'C#', 'ASP.NET Core',
                'REST APIs', 'GraphQL', 'Authentication & Authorization', 'Microservices',
                'MVC Architecture', 'Testing (Unit / Integration / E2E)', 'API Security',
                'Performance Optimization', 'Server-Side Rendering', 'Web Security',
            ],

            'Database & Data Handling' => [
                'MySQL', 'PostgreSQL', 'SQLite', 'MongoDB', 'Firebase', 'Supabase',
                'Redis', 'Elasticsearch', 'Database Design', 'Data Modeling', 'Migrations',
                'ORM (Eloquent / Sequelize / Prisma)', 'Database Indexing', 'ETL Processes',
                'Data Analysis', 'Data Visualization', 'Big Data', 'Pandas', 'NumPy',
                'Data Warehousing', 'Business Intelligence',
            ],

            'DevOps & Deployment' => [
                'Git', 'GitHub', 'GitLab', 'Docker', 'Kubernetes', 'CI/CD', 'GitHub Actions',
                'Jenkins', 'Linux', 'Bash Scripting', 'AWS', 'Google Cloud', 'Azure',
                'Vercel', 'Netlify', 'Heroku', 'DigitalOcean', 'Nginx', 'Apache',
                'Monitoring & Logging', 'Infrastructure as Code (Terraform/Ansible)', 'DevSecOps',
            ],

            'Professional & Soft Skills' => [
                'Communication', 'Teamwork', 'Leadership', 'Project Management', 'Time Management',
                'Problem Solving', 'Critical Thinking', 'Analytical Thinking', 'Creativity',
                'Adaptability', 'Mentoring', 'Public Speaking', 'Technical Writing', 'Documentation',
                'Stakeholder Management', 'Agile Methodology', 'Scrum', 'Kanban',
                'Remote Collaboration', 'Continuous Learning',
            ],
        ];

        foreach ($mapping as $categoryName => $skills) {
            $category = SkillCategory::where('name', $categoryName)->first();

            foreach ($skills as $skillName) {
                $skill = Skill::where('name', $skillName)->first();

                if ($category && $skill) {
                    SkillCategoryLink::firstOrCreate([
                        'skill_id' => $skill->id,
                        'skill_category_id' => $category->id,
                    ]);
                }
            }
        }
    }
}
