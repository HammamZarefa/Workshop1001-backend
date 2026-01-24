<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->user = User::factory()->create([
            'is_admin' => false,
        ]);
    }


    /** @test */
    public function actions_are_not_logged()
    {
        $this->actingAs($this->user, 'sanctum')
            ->deleteJson('/api/v1/admin/orders/1');

        $this->assertDatabaseCount('audit_logs', 0);
    }

    /** @test */
    public function get_requests_are_not_logged()
    {
        $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/v1/admin/audit-logs');

        $this->assertDatabaseCount('audit_logs', 0);
    }

    /** @test */
    public function export_audit_logs_to_csv()
    {
        AuditLog::factory()->create([
            'admin_id' => $this->admin->id,
            'action'   => 'update',
            'resource' => 'orders',
        ]);

        $response = $this->actingAs($this->admin)
            ->get('/admin/audit-logs/export');

        $response->assertStatus(200);
        $this->assertStringContainsString(
            'text/csv',
            $response->headers->get('Content-Type')
        );

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $lines = explode("\n", trim($content));
        $header = str_getcsv($lines[0]);

        $header[0] = preg_replace('/^\x{FEFF}/u', '', $header[0]);

        $this->assertEquals([
            'ID',
            'Action',
            'Admin',
            'IP Address',
            'Created At',
        ], $header);
    }

    /** @test */
    public function retention_job_deletes()
    {
        $oldLog = AuditLog::factory()->create([
            'created_at' => now()->subDays(91),
        ]);

        $newLog = AuditLog::factory()->create([
            'created_at' => now()->subDays(30),
        ]);

        $this->assertDatabaseHas('audit_logs', ['id' => $oldLog->id]);
        $this->assertDatabaseHas('audit_logs', ['id' => $newLog->id]);

        $this->artisan('audit:purge-old')->assertExitCode(0);

        $this->assertDatabaseMissing('audit_logs', ['id' => $oldLog->id]);

        $this->assertDatabaseHas('audit_logs', ['id' => $newLog->id]);
    }

    /** @test */
    public function non_admin_cannot_access_audit_logs()
    {
        $response = $this->actingAs($this->user)
            ->get('/admin/audit-logs');

        $response->assertStatus(403);
        $response->assertStatus(403);
    }

    /** @test */
    public function retention_job_deletes_old_logs()
    {
        $oldLog = AuditLog::factory()->create([
            'created_at' => now()->subDays(91),
        ]);

        $newLog = AuditLog::factory()->create([
            'created_at' => now()->subDays(30),
        ]);

        $this->assertDatabaseHas('audit_logs', ['id' => $oldLog->id]);
        $this->assertDatabaseHas('audit_logs', ['id' => $newLog->id]);

        // تشغيل Artisan job لحذف الـ logs القديمة
        $this->artisan('audit:purge-old')->assertExitCode(0);

        $this->assertDatabaseMissing('audit_logs', ['id' => $oldLog->id]);
        $this->assertDatabaseHas('audit_logs', ['id' => $newLog->id]);
    }

}
