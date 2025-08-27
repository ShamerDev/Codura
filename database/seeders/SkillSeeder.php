<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            // Front-End Development
            'UI/UX Design', 'HTML5', 'CSS3', 'JavaScript', 'TypeScript', 'React.js', 'Vue.js', 'Angular',
            'Svelte', 'Next.js', 'Nuxt.js', 'Tailwind CSS', 'Bootstrap', 'Sass/SCSS', 'Less',
            'Webpack', 'Vite', 'Responsive Design', 'Cross-browser Compatibility', 'Web Accessibility',
            'Progressive Web Apps', 'Single Page Applications', 'Component Architecture', 'State Management',
            'Frontend Testing', 'React Native', 'Flutter', 'Dart', 'Swift', 'iOS Development',
            'Android Development', 'Xamarin', 'Ionic', 'Mobile UI/UX', 'Cross-platform Development',
            'Figma', 'Adobe XD', 'Sketch', 'Wireframing', 'Prototyping', 'Design Systems',

            // Back-End Development
            'PHP', 'Laravel', 'Symfony', 'CodeIgniter', 'Node.js', 'Express.js', 'Python', 'Django',
            'Flask', 'FastAPI', 'Java', 'Spring Boot', 'C#', 'ASP.NET Core', 'Ruby', 'Ruby on Rails',
            'Go', 'Rust', 'Kotlin', 'RESTful APIs', 'GraphQL', 'API Design', 'Microservices',
            'Authentication & Authorization', 'JWT', 'OAuth', 'Session Management', 'CRUD Operations',
            'MVC Architecture', 'Server-side Rendering', 'Unit Testing', 'Integration Testing',
            'End-to-End Testing', 'Test-Driven Development', 'Jest', 'PHPUnit', 'Cypress', 'Selenium',
            'API Testing', 'Performance Testing', 'Load Testing', 'Machine Learning', 'Artificial Intelligence',
            'TensorFlow', 'PyTorch', 'Scikit-learn', 'Web Security', 'HTTPS/SSL', 'Data Encryption',
            'OWASP Top 10', 'SQL Injection Prevention', 'XSS Prevention', 'CSRF Protection',

            // Database & Data Handling
            'MySQL', 'PostgreSQL', 'SQLite', 'MongoDB', 'Redis', 'Elasticsearch', 'Firebase Firestore',
            'Supabase', 'Database Design', 'SQL Optimization', 'NoSQL', 'Data Modeling', 'Migrations',
            'Database Indexing', 'ORM/ODM', 'Eloquent ORM', 'Sequelize', 'Prisma', 'ETL Processes',
            'Data Analysis', 'Data Visualization', 'Big Data', 'Apache Kafka', 'Message Queues',
            'Data Warehousing', 'Deep Learning', 'Natural Language Processing', 'Computer Vision',
            'Blockchain', 'Web3', 'Smart Contracts', 'Solidity', 'Cryptocurrency', 'Data Science',
            'Business Intelligence', 'Data Mining', 'Statistical Analysis', 'Pandas', 'NumPy',

            // DevOps & Deployment
            'Git', 'GitHub', 'GitLab', 'Bitbucket', 'Docker', 'Kubernetes', 'CI/CD', 'GitHub Actions',
            'Jenkins', 'Travis CI', 'Linux', 'Ubuntu', 'CentOS', 'Bash Scripting', 'PowerShell',
            'AWS', 'Amazon EC2', 'Amazon S3', 'AWS Lambda', 'Google Cloud Platform', 'Microsoft Azure',
            'Vercel', 'Netlify', 'Heroku', 'DigitalOcean', 'Nginx', 'Apache', 'Load Balancing',
            'Monitoring & Logging', 'Infrastructure as Code', 'Terraform', 'Ansible', 'Vagrant',
            'CloudFormation', 'Network Security', 'Penetration Testing', 'Vulnerability Assessment',
            'Security Auditing', 'Compliance Standards', 'DevSecOps', 'Container Security',

            // Professional & Soft Skills
            'Communication', 'Teamwork', 'Leadership', 'Project Management', 'Time Management',
            'Problem Solving', 'Critical Thinking', 'Analytical Thinking', 'Creativity', 'Adaptability',
            'Mentoring', 'Public Speaking', 'Technical Writing', 'Documentation', 'Code Architecture',
            'System Design', 'Requirements Analysis', 'Stakeholder Management', 'Agile Methodology',
            'Scrum', 'Kanban', 'Remote Collaboration', 'Client Relations', 'Troubleshooting',
            'Continuous Learning', 'Code Review', 'Quality Assurance', 'Client Communication',
            'Presentation Skills', 'Conflict Resolution', 'Decision Making', 'Strategic Thinking',
            'Innovation', 'Resource Management', 'Risk Assessment', 'Vendor Management'
        ];

        foreach ($skills as $name) {
            Skill::firstOrCreate(['name' => $name]);
        }
    }
}
