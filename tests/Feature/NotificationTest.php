<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SystemNotification;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_mark_notification_as_read()
    {
        $user = User::factory()->create();

        $notification = $user->notify(
            new SystemNotification('Title', 'Message', ['database'])
        );

        $id = $user->notifications()->first()->id;

        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/v1/notifications/{$id}/read");

        $response->assertOk();

        $this->assertNotNull(
            $user->notifications()->first()->read_at
        );
    }

    /** @test */
    public function admin_can_send_broadcast_notification()
    {
        Notification::fake();

        $admin = User::factory()->create(['is_admin' => true]);
        User::factory()->count(3)->create(['is_active' => true]);

        $response = $this->actingAs($admin)
            ->post(route('admin.notifications.store'), [
                'title' => 'Admin notice',
                'message' => 'Hello users',
                'channels' => ['database'],
            ]);

        $response->assertRedirect();

        Notification::assertSentTo(
            User::where('is_active', true)->get(),
            SystemNotification::class
        );
    }
}
