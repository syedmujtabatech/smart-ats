<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\InterviewSchedule;
use App\Models\JobPost;
use App\Models\User;
use App\Services\AiResumeMatcherService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Demo Recruiter Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@talentpulse.io'],
            [
                'name' => 'Sarah Connor (Head of Talent)',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Create Realistic Tech Jobs
        $jobsData = [
            [
                'title' => 'Senior Full-Stack Engineer (Laravel & Vue.js)',
                'department' => 'Engineering',
                'location_type' => 'Remote',
                'location_city' => 'Worldwide',
                'employment_type' => 'Full-time',
                'experience_level' => 'Senior',
                'min_salary' => 120000,
                'max_salary' => 155000,
                'currency' => 'USD',
                'required_skills' => ['Laravel', 'PHP', 'Vue.js', 'PostgreSQL', 'REST APIs', 'Docker', 'Git'],
                'nice_to_have_skills' => ['Redis', 'TailwindCSS', 'AWS', 'CI/CD', 'PHPUnit'],
                'description' => "We are looking for a Senior Full-Stack Engineer to architect and build mission-critical features across our SaaS platform.\n\nYou will work closely with product managers and distributed teams to design high-throughput APIs, optimize relational database queries, and build seamless reactive frontends.",
                'requirements' => "• 5+ years of hands-on experience in modern PHP (8+) and Laravel ecosystem.\n• Strong frontend proficiency with Vue.js 3 / Composition API or React.\n• Mastery in relational database design (PostgreSQL/MySQL) and caching strategies.\n• Familiarity with containerization (Docker) and automated CI/CD pipelines.",
                'status' => 'published',
            ],
            [
                'title' => 'AI / Machine Learning Solutions Architect',
                'department' => 'AI / Machine Learning',
                'location_type' => 'Hybrid',
                'location_city' => 'San Francisco, CA',
                'employment_type' => 'Full-time',
                'experience_level' => 'Lead',
                'min_salary' => 170000,
                'max_salary' => 220000,
                'currency' => 'USD',
                'required_skills' => ['Python', 'PyTorch', 'LangChain', 'Vector DB', 'FastAPI', 'LLM Fine-tuning', 'Embeddings'],
                'nice_to_have_skills' => ['Kubernetes', 'MLflow', 'Triton', 'AWS SageMaker', 'HuggingFace'],
                'description' => "Lead our AI engineering initiatives. You will design and deploy enterprise-grade generative AI pipelines, retrieval-augmented generation (RAG) systems, and multi-agent workflows.",
                'requirements' => "• 6+ years in Python machine learning systems and deep learning frameworks.\n• Proven track record deploying production LLM applications using vector databases (Pinecone, Qdrant, pgvector).\n• Experience optimizing inference latency and orchestrating microservices with FastAPI.",
                'status' => 'published',
            ],
            [
                'title' => 'Senior Cloud DevOps & Platform Engineer',
                'department' => 'DevOps & Cloud',
                'location_type' => 'Remote',
                'location_city' => 'North America / Europe',
                'employment_type' => 'Full-time',
                'experience_level' => 'Senior',
                'min_salary' => 135000,
                'max_salary' => 175000,
                'currency' => 'USD',
                'required_skills' => ['AWS', 'Kubernetes', 'Terraform', 'Docker', 'GitHub Actions', 'Prometheus', 'Linux'],
                'nice_to_have_skills' => ['Helm', 'ArgoCD', 'Grafana', 'Golang', 'Security / SOC2'],
                'description' => "Own our cloud infrastructure reliability, multi-region deployments, and developer tooling.\n\nYou will automate zero-downtime releases, manage Kubernetes clusters on AWS, and establish observability across all microservices.",
                'requirements' => "• 5+ years supporting high-availability production infrastructure on AWS or GCP.\n• Deep expertise in Kubernetes cluster management, Helm charts, and Terraform IaC.\n• Hands-on experience building GitOps workflows with GitHub Actions and ArgoCD.",
                'status' => 'published',
            ],
            [
                'title' => 'Senior Product Designer (UI / UX Systems)',
                'department' => 'Product & Design',
                'location_type' => 'Remote',
                'location_city' => 'Worldwide',
                'employment_type' => 'Full-time',
                'experience_level' => 'Senior',
                'min_salary' => 115000,
                'max_salary' => 150000,
                'currency' => 'USD',
                'required_skills' => ['Figma', 'UI Design', 'Design Systems', 'User Research', 'Prototyping', 'Wireframing'],
                'nice_to_have_skills' => ['HTML/CSS', 'TailwindCSS', 'Motion Design', 'Micro-interactions'],
                'description' => "Craft intuitive, beautiful software experiences. You will lead the design system, conduct user interviews with enterprise clients, and turn complex data visualizations into delight.",
                'requirements' => "• 5+ years of product design experience for complex B2B SaaS or developer tools.\n• Proven mastery in Figma components, auto-layout, and tokenized design systems.\n• Strong portfolio demonstrating end-to-end design thinking and interactive prototypes.",
                'status' => 'published',
            ],
            [
                'title' => 'Frontend Engineer (React / Next.js & TypeScript)',
                'department' => 'Engineering',
                'location_type' => 'Hybrid',
                'location_city' => 'New York, NY',
                'employment_type' => 'Full-time',
                'experience_level' => 'Mid-Level',
                'min_salary' => 105000,
                'max_salary' => 135000,
                'currency' => 'USD',
                'required_skills' => ['React', 'Next.js', 'TypeScript', 'TailwindCSS', 'REST APIs', 'State Management'],
                'nice_to_have_skills' => ['GraphQL', 'Zustand', 'React Flow', 'Playwright', 'WebSockets'],
                'description' => "Build responsive, high-performance web applications using React 19 and Next.js App Router.",
                'requirements' => "• 3+ years experience with React, TypeScript, and modern styling libraries.\n• Strong grasp of SSR, hydration, and client-side performance tuning.\n• Experience integrating complex third-party APIs and WebSocket real-time feeds.",
                'status' => 'published',
            ],
        ];

        $createdJobs = [];
        foreach ($jobsData as $jd) {
            $createdJobs[] = JobPost::create([
                'title' => $jd['title'],
                'slug' => Str::slug($jd['title']) . '-' . Str::lower(Str::random(5)),
                'department' => $jd['department'],
                'location_type' => $jd['location_type'],
                'location_city' => $jd['location_city'],
                'employment_type' => $jd['employment_type'],
                'experience_level' => $jd['experience_level'],
                'min_salary' => $jd['min_salary'],
                'max_salary' => $jd['max_salary'],
                'currency' => $jd['currency'],
                'description' => $jd['description'],
                'requirements' => $jd['requirements'],
                'required_skills' => $jd['required_skills'],
                'nice_to_have_skills' => $jd['nice_to_have_skills'],
                'status' => $jd['status'],
                'deadline' => now()->addDays(30),
                'views_count' => rand(45, 180),
            ]);
        }

        // 3. Seed Candidates with Detailed Resumes & Pre-Evaluated Match Scores
        $jobFullStack = $createdJobs[0];
        $jobAi = $createdJobs[1];
        $jobDevOps = $createdJobs[2];
        $jobDesigner = $createdJobs[3];

        $candidatesData = [
            // Top match for Full-Stack
            [
                'name' => 'Alexander Vance',
                'email' => 'alex.vance@techdev.io',
                'phone' => '+1 (415) 882-9011',
                'linkedin_url' => 'https://linkedin.com/in/alexandervance-dev',
                'github_url' => 'https://github.com/alexvance-code',
                'portfolio_url' => 'https://alexvance.dev',
                'current_company' => 'Stripe Solutions',
                'current_role' => 'Senior Software Engineer',
                'years_of_experience' => 6.5,
                'skills' => ['Laravel', 'PHP', 'Vue.js', 'PostgreSQL', 'Docker', 'Redis', 'TailwindCSS', 'REST APIs', 'AWS'],
                'target_job' => $jobFullStack,
                'stage' => 'interview',
                'ai_match_score' => 94,
                'ai_recommendation' => 'Strong Match',
                'ai_strengths' => [
                    'Over 6 years deep experience in PHP 8 and Laravel architecture with production apps.',
                    'Extensive frontend experience with Vue.js 3 Composition API and responsive UI.',
                    'Direct experience managing PostgreSQL query optimization and Redis queue systems.',
                    'Solid understanding of Docker containers and automated CI/CD pipelines.'
                ],
                'ai_missing_skills' => [
                    'Limited explicit mentions of PHPUnit coverage in resume overview.'
                ],
                'ai_summary' => 'Alexander is an exceptional candidate for the Senior Full-Stack Engineer role. His track record at Stripe Solutions showcases mastery across both Laravel backend API design and reactive Vue.js frontends. Strongly recommended for technical interview round.',
                'ai_sentiment' => 'Positive',
                'resume_text' => "ALEXANDER VANCE - SENIOR SOFTWARE ENGINEER\nEmail: alex.vance@techdev.io | GitHub: github.com/alexvance-code\n\nSUMMARY:\nSenior Full-Stack Engineer with 6.5+ years of experience architecting high-throughput distributed applications in PHP (Laravel) and Vue.js. Led database optimization projects reducing API latency by 45%.\n\nEXPERIENCE:\nSenior Software Engineer - Stripe Solutions (2022 - Present)\n• Designed core billing APIs in Laravel handling 3M+ monthly transactions.\n• Built customer analytics portal using Vue.js 3, Pinia, and TailwindCSS.\n• Implemented Redis caching and horizontal scaling on AWS ECS.\n\nSoftware Engineer - CloudScale (2019 - 2022)\n• Built RESTful microservices with PHP 8, Laravel, and PostgreSQL.\n• Containerized local development environments with Docker Compose.\n• Created automated CI/CD testing pipelines using GitHub Actions.",
            ],
            // Mid match for Full-Stack
            [
                'name' => 'David Kim',
                'email' => 'david.kim@techcraft.net',
                'phone' => '+1 (212) 443-1289',
                'linkedin_url' => 'https://linkedin.com/in/davidkim-swe',
                'github_url' => 'https://github.com/davidkim-dev',
                'portfolio_url' => null,
                'current_company' => 'Acme Technologies',
                'current_role' => 'Full-Stack Developer',
                'years_of_experience' => 3.5,
                'skills' => ['PHP', 'Laravel', 'JavaScript', 'MySQL', 'Git', 'Bootstrap'],
                'target_job' => $jobFullStack,
                'stage' => 'screening',
                'ai_match_score' => 74,
                'ai_recommendation' => 'Potential Match',
                'ai_strengths' => [
                    'Competent foundations in core PHP and Laravel MVC conventions.',
                    'Solid experience building relational databases with MySQL.'
                ],
                'ai_missing_skills' => [
                    'Vue.js experience is minimal compared to vanilla JavaScript.',
                    'Docker containerization not highlighted in employment history.',
                    'Slightly below target seniority (3.5y vs 5y expected).'
                ],
                'ai_summary' => 'David possesses solid foundational knowledge of Laravel and backend architecture, but exhibits some gaps in advanced Vue.js 3 and container orchestration. Candidate has strong growth potential for technical screening.',
                'ai_sentiment' => 'Positive',
                'resume_text' => "DAVID KIM - FULL-STACK DEVELOPER\n\nEXPERIENCE:\nFull-Stack Developer - Acme Technologies (2023 - Present)\n• Built customer management modules in Laravel and MySQL.\n• Created reporting dashboards with JavaScript and Chart.js.\n• Integrated third-party webhooks for invoice generation.\n\nJunior Web Developer - WebWorks Agency (2021 - 2023)\n• Developed custom WordPress and Laravel web applications for clients.\n• Maintained Git version control and handled staging deployments.",
            ],
            // Low match for Full-Stack
            [
                'name' => 'Liam O\'Connor',
                'email' => 'liam.oconnor@codeworks.org',
                'phone' => '+44 20 7946 0192',
                'linkedin_url' => 'https://linkedin.com/in/liamoconnor',
                'github_url' => null,
                'portfolio_url' => null,
                'current_company' => 'Digital Forge',
                'current_role' => 'Junior Frontend Dev',
                'years_of_experience' => 1.5,
                'skills' => ['HTML', 'CSS', 'JavaScript', 'React basics'],
                'target_job' => $jobFullStack,
                'stage' => 'rejected',
                'ai_match_score' => 42,
                'ai_recommendation' => 'Low Match',
                'ai_strengths' => [
                    'Demonstrated enthusiasm for web development and JavaScript fundamentals.'
                ],
                'ai_missing_skills' => [
                    'No verifiable PHP or Laravel backend experience.',
                    'Missing PostgreSQL, Docker, and REST API architectural background.',
                    'Underqualified for Senior role requirements.'
                ],
                'ai_summary' => 'Profile does not meet the technical seniority or backend prerequisites required for this Senior Laravel position. Significant competency gaps identified.',
                'ai_sentiment' => 'Negative',
                'resume_text' => "LIAM O'CONNOR - JUNIOR DEVELOPER\n1.5 years experience building static websites with HTML, CSS, JavaScript, and introductory React components.",
            ],
            // Top match for AI Solutions Architect
            [
                'name' => 'Dr. Elena Rostova',
                'email' => 'elena.rostova@ai-research.co',
                'phone' => '+1 (650) 902-8841',
                'linkedin_url' => 'https://linkedin.com/in/elena-rostova-phd',
                'github_url' => 'https://github.com/erostova-ai',
                'portfolio_url' => 'https://rostova.ai',
                'current_company' => 'Anthropic Labs Partner',
                'current_role' => 'Principal AI Research Engineer',
                'years_of_experience' => 7.5,
                'skills' => ['Python', 'PyTorch', 'LangChain', 'Vector DB', 'FastAPI', 'LLM Fine-tuning', 'Embeddings', 'Qdrant', 'Kubernetes'],
                'target_job' => $jobAi,
                'stage' => 'hr_round',
                'ai_match_score' => 97,
                'ai_recommendation' => 'Strong Match',
                'ai_strengths' => [
                    'Exceptional domain depth in LLM fine-tuning, RAG pipelines, and vector embeddings.',
                    'Over 7 years deploying production Python AI microservices with FastAPI.',
                    'Proven experience managing enterprise vector databases (pgvector, Qdrant) at scale.',
                    'Published research in semantic search and multi-agent coordination.'
                ],
                'ai_missing_skills' => [
                    'None identified. Candidate meets or exceeds all prerequisites.'
                ],
                'ai_summary' => 'Outstanding candidate for the AI Solutions Architect requisition. Elena possesses top-tier expertise spanning PyTorch, vector embeddings, and production LLM orchestration with LangChain and FastAPI. Candidate moved to final HR round.',
                'ai_sentiment' => 'Positive',
                'resume_text' => "DR. ELENA ROSTOVA - PRINCIPAL AI RESEARCH ENGINEER\nPhD in Computer Science (NLP & Embeddings)\n\nEXPERIENCE:\nPrincipal AI Engineer - AI Systems (2021 - Present)\n• Architected enterprise RAG pipeline serving 50k daily active users.\n• Fine-tuned open-source LLMs using LoRA/PEFT on multi-GPU clusters.\n• Deployed low-latency vector search services using FastAPI and Qdrant.\n\nSenior ML Engineer - NeuralScale (2018 - 2021)\n• Built Python machine learning pipelines using PyTorch and Docker.\n• Integrated semantic search systems with custom sentence-transformer models.",
            ],
            // Another AI Candidate
            [
                'name' => 'Kavita Patel',
                'email' => 'kavita.patel@mlpulse.io',
                'phone' => '+1 (408) 773-0199',
                'linkedin_url' => 'https://linkedin.com/in/kavita-patel-ml',
                'github_url' => 'https://github.com/kavitapatel',
                'portfolio_url' => null,
                'current_company' => 'DataCore AI',
                'current_role' => 'Senior Python & ML Engineer',
                'years_of_experience' => 5.0,
                'skills' => ['Python', 'FastAPI', 'LangChain', 'Vector DB', 'Docker', 'AWS'],
                'target_job' => $jobAi,
                'stage' => 'interview',
                'ai_match_score' => 88,
                'ai_recommendation' => 'Strong Match',
                'ai_strengths' => [
                    '5 years hands-on production Python and FastAPI microservices development.',
                    'Practical experience deploying LangChain RAG workflows and Vector DB search.'
                ],
                'ai_missing_skills' => [
                    'PyTorch custom model fine-tuning experience is slightly limited.'
                ],
                'ai_summary' => 'Kavita has robust industry experience deploying production AI microservices and LangChain pipelines. Very solid candidate for technical architecture interview.',
                'ai_sentiment' => 'Positive',
                'resume_text' => "KAVITA PATEL - SENIOR PYTHON & ML ENGINEER\n5 years experience building scalable backend microservices and modern LLM application workflows.",
            ],
            // Top match for DevOps Engineer
            [
                'name' => 'Marcus Sterling',
                'email' => 'marcus.sterling@cloudops.dev',
                'phone' => '+1 (206) 555-8821',
                'linkedin_url' => 'https://linkedin.com/in/marcussterling',
                'github_url' => 'https://github.com/msterling-infra',
                'portfolio_url' => null,
                'current_company' => 'CloudNative Corp',
                'current_role' => 'Lead DevOps Engineer',
                'years_of_experience' => 7.0,
                'skills' => ['AWS', 'Kubernetes', 'Terraform', 'Docker', 'GitHub Actions', 'Prometheus', 'Grafana', 'ArgoCD'],
                'target_job' => $jobDevOps,
                'stage' => 'offered',
                'ai_match_score' => 95,
                'ai_recommendation' => 'Strong Match',
                'ai_strengths' => [
                    '7 years managing multi-region Kubernetes clusters on AWS.',
                    'Deep expertise in Terraform infrastructure-as-code and GitOps using ArgoCD.',
                    'Complete monitoring stack implementation with Prometheus and Grafana.'
                ],
                'ai_missing_skills' => [
                    'Minimal Golang coding compared to infrastructure automation scripts.'
                ],
                'ai_summary' => 'Marcus is an ideal candidate for Lead DevOps. Strong background in zero-downtime deployments, Kubernetes orchestration, and SOC2 compliance. Offer extended.',
                'ai_sentiment' => 'Positive',
                'resume_text' => "MARCUS STERLING - LEAD DEVOPS & INFRASTRUCTURE\nProven track record managing 200+ node Kubernetes clusters on AWS with automated Terraform and GitOps pipelines.",
            ],
            // Top match for Product Designer
            [
                'name' => 'Sophia Chen',
                'email' => 'sophia.chen@uxstudio.design',
                'phone' => '+1 (312) 884-2104',
                'linkedin_url' => 'https://linkedin.com/in/sophiachen-design',
                'github_url' => null,
                'portfolio_url' => 'https://sophiachen.design',
                'current_company' => 'Figma Systems Partner',
                'current_role' => 'Senior Product Designer',
                'years_of_experience' => 5.5,
                'skills' => ['Figma', 'UI Design', 'Design Systems', 'User Research', 'Prototyping', 'TailwindCSS'],
                'target_job' => $jobDesigner,
                'stage' => 'applied',
                'ai_match_score' => 91,
                'ai_recommendation' => 'Strong Match',
                'ai_strengths' => [
                    'Mastery in Figma component libraries and tokenized design systems.',
                    'Proven experience leading enterprise SaaS UI/UX redesigns with user research.'
                ],
                'ai_missing_skills' => [
                    'None critical.'
                ],
                'ai_summary' => 'Sophia presents a stellar portfolio in modern B2B SaaS design systems. High attention to detail and strong user research capability.',
                'ai_sentiment' => 'Positive',
                'resume_text' => "SOPHIA CHEN - SENIOR PRODUCT DESIGNER\n5.5 years designing enterprise data workflows and intuitive interfaces in Figma.",
            ],
            // Additional candidates across stages
            [
                'name' => 'Hassan Raza',
                'email' => 'hassan.raza@fullstack.dev',
                'phone' => '+92 300 1234567',
                'linkedin_url' => 'https://linkedin.com/in/hassanraza-dev',
                'github_url' => 'https://github.com/hassanraza-pk',
                'portfolio_url' => 'https://hassanraza.io',
                'current_company' => 'Systems Ltd',
                'current_role' => 'Senior Backend Engineer',
                'years_of_experience' => 5.2,
                'skills' => ['Laravel', 'PHP', 'PostgreSQL', 'Docker', 'Redis', 'REST APIs', 'Git'],
                'target_job' => $jobFullStack,
                'stage' => 'applied',
                'ai_match_score' => 89,
                'ai_recommendation' => 'Strong Match',
                'ai_strengths' => [
                    '5+ years building scalable enterprise Laravel APIs with PostgreSQL and Redis.',
                    'Strong familiarity with Docker workflows and automated testing.'
                ],
                'ai_missing_skills' => [
                    'Vue.js frontend skills require further assessment.'
                ],
                'ai_summary' => 'Hassan has an impressive background in backend architecture and high-load Laravel APIs. Recommended for immediate screening.',
                'ai_sentiment' => 'Positive',
                'resume_text' => "HASSAN RAZA - SENIOR BACKEND ENGINEER\n5.2 years building high-load APIs in Laravel, PHP 8, and PostgreSQL.",
            ],
            [
                'name' => 'Zoe Martinez',
                'email' => 'zoe.martinez@cloudscale.net',
                'phone' => '+1 (512) 662-9011',
                'linkedin_url' => 'https://linkedin.com/in/zoemartinez',
                'github_url' => null,
                'portfolio_url' => null,
                'current_company' => 'Austin Cloud Systems',
                'current_role' => 'DevOps Specialist',
                'years_of_experience' => 4.0,
                'skills' => ['AWS', 'Docker', 'Terraform', 'Linux', 'GitHub Actions'],
                'target_job' => $jobDevOps,
                'stage' => 'screening',
                'ai_match_score' => 82,
                'ai_recommendation' => 'Potential Match',
                'ai_strengths' => [
                    'Solid foundation in AWS cloud architectures and Terraform IaC.'
                ],
                'ai_missing_skills' => [
                    'Production Kubernetes cluster administration experience is nascent.'
                ],
                'ai_summary' => 'Zoe shows great competence in AWS automation and container workflows. Good candidate for technical round.',
                'ai_sentiment' => 'Positive',
                'resume_text' => "ZOE MARTINEZ - DEVOPS ENGINEER\n4 years experience supporting CI/CD automation and AWS cloud infrastructure.",
            ],
        ];

        foreach ($candidatesData as $c) {
            $candidate = Candidate::create([
                'name' => $c['name'],
                'email' => $c['email'],
                'phone' => $c['phone'],
                'linkedin_url' => $c['linkedin_url'],
                'github_url' => $c['github_url'],
                'portfolio_url' => $c['portfolio_url'],
                'current_company' => $c['current_company'],
                'current_role' => $c['current_role'],
                'years_of_experience' => $c['years_of_experience'],
                'skills' => $c['skills'],
            ]);

            $app = Application::create([
                'job_post_id' => $c['target_job']->id,
                'candidate_id' => $candidate->id,
                'stage' => $c['stage'],
                'resume_text' => $c['resume_text'],
                'ai_match_score' => $c['ai_match_score'],
                'ai_status' => 'analyzed',
                'ai_recommendation' => $c['ai_recommendation'],
                'ai_strengths' => $c['ai_strengths'],
                'ai_missing_skills' => $c['ai_missing_skills'],
                'ai_summary' => $c['ai_summary'],
                'ai_sentiment' => $c['ai_sentiment'],
                'applied_at' => now()->subDays(rand(1, 10)),
                'ai_analyzed_at' => now()->subDays(rand(1, 9)),
            ]);

            // Add an activity log
            ActivityLog::create([
                'application_id' => $app->id,
                'user_id' => $admin->id,
                'action' => 'ai_evaluated',
                'description' => "Candidate {$candidate->name} evaluated by AI. Score: {$c['ai_match_score']}% ({$c['ai_recommendation']})",
                'created_at' => now()->subDays(rand(1, 5)),
            ]);

            // Add interview schedule for those in interview or higher
            if (in_array($c['stage'], ['interview', 'hr_round', 'offered'])) {
                InterviewSchedule::create([
                    'application_id' => $app->id,
                    'interviewer_name' => 'Sarah Connor (Talent Lead)',
                    'interview_type' => $c['stage'] === 'hr_round' ? 'HR & Culture Fit' : 'Technical Architecture Interview',
                    'scheduled_at' => now()->addDays(rand(1, 4))->setTime(14, 0),
                    'duration_minutes' => 45,
                    'meeting_link' => 'https://meet.google.com/talent-pulse-demo',
                    'status' => 'scheduled',
                    'feedback' => 'Candidate demonstrated impressive depth during initial review.',
                ]);
            }
        }
    }
}
