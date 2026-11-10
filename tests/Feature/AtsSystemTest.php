<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\JobPost;
use App\Models\User;
use App\Services\AiResumeMatcherService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AtsSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_careers_page_loads_successfully(): void
    {
        $response = $this->get('/careers');
        $response->assertStatus(200);
        $response->assertSee('TalentPulse');
    }

    public function test_login_page_loads_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Demo Recruiter');
    }

    public function test_demo_login_authenticates_and_redirects(): void
    {
        $response = $this->post('/login/demo');
        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticated();
    }

    public function test_authenticated_admin_can_view_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Recruitment Intelligence Dashboard');
    }

    public function test_ai_resume_matcher_calculates_score_and_strengths(): void
    {
        $job = JobPost::create([
            'title' => 'Laravel Backend Engineer',
            'slug' => 'laravel-backend-engineer',
            'department' => 'Engineering',
            'experience_level' => 'Senior',
            'required_skills' => ['Laravel', 'PHP', 'PostgreSQL', 'Docker'],
            'description' => 'We need an experienced Laravel engineer.',
            'status' => 'published',
        ]);

        $candidate = Candidate::create([
            'name' => 'Jane Developer',
            'email' => 'jane@example.com',
            'current_role' => 'Software Engineer',
            'years_of_experience' => 5.0,
            'skills' => ['Laravel', 'PHP', 'PostgreSQL', 'Docker', 'Redis'],
        ]);

        $application = Application::create([
            'job_post_id' => $job->id,
            'candidate_id' => $candidate->id,
            'resume_text' => '5 years experience working with PHP 8, Laravel architecture, Docker, and PostgreSQL databases.',
            'stage' => 'applied',
        ]);

        $service = new AiResumeMatcherService();
        $result = $service->evaluate($application);

        $this->assertGreaterThanOrEqual(70, $result['match_score']);
        $this->assertEquals('Strong Match', $result['recommendation']);
        $this->assertNotEmpty($result['strengths']);
    }
}
