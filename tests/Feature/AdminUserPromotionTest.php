<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserPromotionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Disable CSRF middleware for test requests
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    public function test_admin_can_promote_and_demote_users()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.users.promote', $user))
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', ['id' => $user->id, 'role' => User::ROLE_ADMIN]);

        // Demote
        $this->actingAs($admin)
            ->post(route('admin.users.demote', $user))
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', ['id' => $user->id, 'role' => User::ROLE_COUNSELOR]);
    }

    public function test_non_admin_cannot_promote()
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.users.promote', $other))
            ->assertStatus(403);
    }
}
