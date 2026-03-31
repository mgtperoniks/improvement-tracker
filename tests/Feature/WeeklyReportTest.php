<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WeeklyPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WeeklyReportTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $manager;
    protected $spv;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->manager = User::factory()->create(['role' => 'manager']);
        $this->spv = User::factory()->create(['role' => 'spv']);
    }

    public function test_manager_can_access_dashboard()
    {
        $response = $this->actingAs($this->manager)->get('/');
        $response->assertStatus(200);
    }

    public function test_manager_can_access_rankings()
    {
        $response = $this->actingAs($this->manager)->get('/rankings');
        $response->assertStatus(200);
    }

    public function test_manager_can_access_weekly_reports_index()
    {
        $response = $this->actingAs($this->manager)->get('/weekly-reports');
        $response->assertStatus(200);
        $response->assertViewIs('weekly-reports.index');
    }

    public function test_admin_can_access_weekly_reports_index()
    {
        $response = $this->actingAs($this->admin)->get('/weekly-reports');
        $response->assertStatus(200);
    }

    public function test_spv_cannot_access_weekly_reports_index()
    {
        $response = $this->actingAs($this->spv)->get('/weekly-reports');
        $response->assertStatus(403);
    }

    public function test_manager_can_access_weekly_reports_detail()
    {
        $plan = WeeklyPlan::factory()->create([
            'user_id' => $this->spv->id,
            'week_start_date' => '2026-03-30',
            'week_end_date' => '2026-04-05',
        ]);

        $response = $this->actingAs($this->manager)->get('/weekly-reports/2026-03-30');
        $response->assertStatus(200);
        $response->assertViewIs('weekly-reports.show');
        $response->assertSee($plan->title);
    }
}
