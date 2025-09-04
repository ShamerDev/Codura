<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            // Front-End Development (22)
            'HTML', 'CSS', 'JavaScript', 'TypeScript', 'React', 'Vue.js', 'Angular', 'Next.js',
            'Nuxt.js', 'Tailwind CSS', 'Bootstrap', 'Sass/SCSS', 'Responsive Design', 'UI/UX Design',
            'Accessibility (a11y)', 'State Management', 'Component Architecture', 'Single Page Applications (SPA)',
            'Progressive Web Apps (PWA)', 'Frontend Testing', 'React Native', 'Flutter', 'Figma',

            // Back-End Development (23)
            'PHP', 'Laravel', 'Symfony', 'Node.js', 'Express.js', 'Python', 'Django', 'Flask',
            'FastAPI', 'Java', 'Spring Boot', 'C#', 'ASP.NET Core', 'REST APIs', 'GraphQL',
            'Authentication & Authorization', 'Microservices', 'MVC Architecture',
            'Testing (Unit / Integration / E2E)', 'API Security', 'Performance Optimization',
            'Server-Side Rendering', 'Web Security',

            // Database & Data Handling (21)
            'MySQL', 'PostgreSQL', 'SQLite', 'MongoDB', 'Firebase', 'Supabase', 'Redis',
            'Elasticsearch', 'Database Design', 'Data Modeling', 'Migrations',
            'ORM (Eloquent / Sequelize / Prisma)', 'Database Indexing', 'ETL Processes',
            'Data Analysis', 'Data Visualization', 'Big Data', 'Pandas', 'NumPy',
            'Data Warehousing', 'Business Intelligence',

            // DevOps & Deployment (22)
            'Git', 'GitHub', 'GitLab', 'Docker', 'Kubernetes', 'CI/CD', 'GitHub Actions',
            'Jenkins', 'Linux', 'Bash Scripting', 'AWS', 'Google Cloud', 'Azure', 'Vercel',
            'Netlify', 'Heroku', 'DigitalOcean', 'Nginx', 'Apache',
            'Monitoring & Logging', 'Infrastructure as Code (Terraform/Ansible)', 'DevSecOps',

            // Professional & Soft Skills (20)
            'Communication', 'Teamwork', 'Leadership', 'Project Management', 'Time Management',
            'Problem Solving', 'Critical Thinking', 'Analytical Thinking', 'Creativity',
            'Adaptability', 'Mentoring', 'Public Speaking', 'Technical Writing', 'Documentation',
            'Stakeholder Management', 'Agile Methodology', 'Scrum', 'Kanban',
            'Remote Collaboration', 'Continuous Learning',
        ];

        foreach ($skills as $name) {
            Skill::firstOrCreate(['name' => $name]);
        }
    }
}
