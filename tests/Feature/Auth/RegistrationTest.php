<?php

namespace Tests\Feature\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->post('/register', [
            'name' => 'Test User',
            'email' => 'test_register@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => \App\Models\User::ROLE_STUDENT,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('student.dashboard'));
    }
}
