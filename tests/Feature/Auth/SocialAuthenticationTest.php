<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\SocialAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;

class SocialAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.github.client_id' => 'valid-github-id',
            'services.github.client_secret' => 'valid-github-secret',
        ]);
    }

    public function test_social_login_redirect()
    {
        $response = $this->get(route('auth.social', 'github'));

        // Socialite redirects to the provider URL
        $response->assertRedirect();
        $this->assertStringContainsString('github.com', $response->headers->get('Location'));
    }

    public function test_social_login_unsupported_provider()
    {
        $response = $this->get(route('auth.social', 'unknown'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
    }

    public function test_social_callback_redirects_to_complete_for_new_user()
    {
        // Mock Socialite User
        $abstractUser = $this->createMock(\Laravel\Socialite\Two\User::class);
        $abstractUser->method('getId')->willReturn('123456');
        $abstractUser->method('getEmail')->willReturn('john@example.com');
        $abstractUser->method('getName')->willReturn('John Doe');
        $abstractUser->method('getAvatar')->willReturn('https://example.com/avatar.jpg');
        $abstractUser->token = 'mock-token';
        $abstractUser->refreshToken = 'mock-refresh-token';

        // Mock Socialite Provider
        $providerMock = $this->getMockBuilder(\Laravel\Socialite\Two\GithubProvider::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['user'])
            ->getMock();
        $providerMock->method('user')->willReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('github')->andReturn($providerMock);

        $response = $this->get(route('auth.social.callback', 'github'));

        // Assert redirect to complete registration page
        $response->assertRedirect(route('auth.social.complete'));
        $response->assertSessionHas('social_register');

        // User should not be created in database yet
        $this->assertDatabaseMissing('users', [
            'email' => 'john@example.com',
        ]);
    }

    public function test_social_complete_registration_form_rendering()
    {
        $socialData = [
            'provider' => 'github',
            'provider_id' => '123456',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'token' => 'mock-token',
            'avatar' => 'https://example.com/avatar.jpg',
        ];

        $response = $this->withSession(['social_register' => $socialData])
            ->get(route('auth.social.complete'));

        $response->assertStatus(200);
        $response->assertSee('Finaliser votre');
        $response->assertSee('john@example.com');
    }

    public function test_social_complete_registration_submits_successfully_for_student()
    {
        $socialData = [
            'provider' => 'github',
            'provider_id' => '123456',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'token' => 'mock-token',
            'avatar' => 'https://example.com/avatar.jpg',
        ];

        $response = $this->withSession(['social_register' => $socialData])
            ->post('auth/social/complete', [
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'student',
            ]);

        // Assert user created as student and approved
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'role' => User::ROLE_STUDENT,
            'status' => User::STATUS_APPROVED,
        ]);

        $user = User::where('email', 'john@example.com')->first();

        // Assert student profile created
        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
        ]);

        // Assert social account linked
        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider_name' => 'github',
            'provider_id' => '123456',
        ]);

        // Assert logged in and redirected to student dashboard
        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('student.dashboard'));
    }

    public function test_social_complete_registration_submits_successfully_for_counselor()
    {
        $socialData = [
            'provider' => 'github',
            'provider_id' => '123456',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'token' => 'mock-token',
            'avatar' => 'https://example.com/avatar.jpg',
        ];

        $response = $this->withSession(['social_register' => $socialData])
            ->post('auth/social/complete', [
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'counselor',
                'phone' => '+21699999999',
                'specialty' => 'Orientation',
                'experience_years' => 5,
                'bio' => 'Experienced counselor',
            ]);

        // Assert user created as counselor pending and status pending approval
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'role' => User::ROLE_COUNSELOR_PENDING,
            'status' => User::STATUS_PENDING_APPROVAL,
        ]);

        $user = User::where('email', 'john@example.com')->first();

        // Assert counselor profile created
        $this->assertDatabaseHas('counselor_profiles', [
            'user_id' => $user->id,
            'phone' => '+21699999999',
            'specialty' => 'Orientation',
            'experience_years' => 5,
            'bio' => 'Experienced counselor',
        ]);

        // Assert social account linked
        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider_name' => 'github',
            'provider_id' => '123456',
        ]);

        // Assert logged in and redirected to counselor pending page
        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('counselor.pending'));
    }

    public function test_social_callback_logs_in_existing_social_user()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'role' => User::ROLE_STUDENT,
        ]);

        SocialAccount::create([
            'user_id' => $user->id,
            'provider_name' => 'github',
            'provider_id' => '123456',
            'token' => 'old-token',
        ]);

        // Mock Socialite User
        $abstractUser = $this->createMock(\Laravel\Socialite\Two\User::class);
        $abstractUser->method('getId')->willReturn('123456');
        $abstractUser->method('getEmail')->willReturn('john@example.com');
        $abstractUser->method('getName')->willReturn('John Doe');
        $abstractUser->method('getAvatar')->willReturn('https://example.com/avatar.jpg');
        $abstractUser->token = 'new-token';
        $abstractUser->refreshToken = 'new-refresh-token';

        // Mock Socialite Provider
        $providerMock = $this->getMockBuilder(\Laravel\Socialite\Two\GithubProvider::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['user'])
            ->getMock();
        $providerMock->method('user')->willReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('github')->andReturn($providerMock);

        $response = $this->get(route('auth.social.callback', 'github'));

        // Assert logged in and token updated
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider_name' => 'github',
            'provider_id' => '123456',
            'token' => 'new-token',
        ]);
        $response->assertRedirect(route('student.dashboard'));
    }

    public function test_social_callback_links_to_existing_local_user()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'role' => User::ROLE_STUDENT,
        ]);

        // Mock Socialite User
        $abstractUser = $this->createMock(\Laravel\Socialite\Two\User::class);
        $abstractUser->method('getId')->willReturn('123456');
        $abstractUser->method('getEmail')->willReturn('john@example.com');
        $abstractUser->method('getName')->willReturn('John Doe');
        $abstractUser->method('getAvatar')->willReturn('https://example.com/avatar.jpg');
        $abstractUser->token = 'mock-token';
        $abstractUser->refreshToken = 'mock-refresh-token';

        // Mock Socialite Provider
        $providerMock = $this->getMockBuilder(\Laravel\Socialite\Two\GithubProvider::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['user'])
            ->getMock();
        $providerMock->method('user')->willReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('github')->andReturn($providerMock);

        $response = $this->get(route('auth.social.callback', 'github'));

        // Assert logged in and linked
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider_name' => 'github',
            'provider_id' => '123456',
        ]);
        $response->assertRedirect(route('student.dashboard'));
    }
}
