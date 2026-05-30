<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterWithRoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register_with_role()
    {
        // Disable CSRF for this test to simulate form POST from UI
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'register-role@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'counselor',
            'phone' => '+21699999999',
            'specialty' => 'Orientation scolaire',
            'experience_years' => 5,
            'bio' => 'Ceci est une biographie professionnelle.',
        ]);

        $response->assertRedirect(route('counselor.pending'));

        $this->assertDatabaseHas('users', [
            'email' => 'register-role@example.com',
            'role' => User::ROLE_COUNSELOR_PENDING,
        ]);

        $user = User::where('email', 'register-role@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->isCounselor());
    }

    /** @test */
    public function student_registration_redirects_to_student_dashboard()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $response = $this->post('/register', [
            'name' => 'Student User',
            'email' => 'student-register@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => User::ROLE_STUDENT,
        ]);

        $response->assertRedirect(route('student.dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => 'student-register@example.com',
            'role' => User::ROLE_STUDENT,
        ]);

        $user = User::where('email', 'student-register@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->isStudent());

        // The student should see a personalized welcome message on the student dashboard
        $this->actingAs($user)
            ->get(route('student.dashboard'))
            ->assertSee('Bienvenue, Student User', false)
            ->assertSee($user->name);
    }
}
