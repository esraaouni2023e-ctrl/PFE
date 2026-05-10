<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSeesNewRegisteredUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_sees_new_user_after_registration()
    {
        // Disable CSRF for the test
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        // Register a new user (simulate public registration)
        $email = 'visible@example.com';
        $this->post('/register', [
            'name' => 'Visible User',
            'email' => $email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => User::ROLE_STUDENT,
        ]);

        // Create an admin and check admin users list
        $admin = User::factory()->admin()->create();

        // Refresh to ensure is_admin is loaded
        $admin->refresh();

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee($email);
        $response->assertSee('New');
    }
}
