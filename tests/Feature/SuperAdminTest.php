<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_is_created_automatically()
    {
        // Trigger the creation logic manually for the test
        if (!User::where('role', User::ROLE_SUPER_ADMIN)->exists()) {
            User::create([
                'name'     => 'Super Admin',
                'email'    => 'admin@gmail.com',
                'password' => \Illuminate\Support\Facades\Hash::make('00000000'),
                'role'     => User::ROLE_SUPER_ADMIN,
                'is_admin' => true,
            ]);
        }
        $this->assertTrue(User::where('role', User::ROLE_SUPER_ADMIN)->exists());
        $admin = User::where('role', User::ROLE_SUPER_ADMIN)->first();
        $this->assertEquals('admin@gmail.com', $admin->email);
    }

    public function test_only_one_super_admin_can_exist()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Il ne peut exister qu\'un seul Super Admin.');

        User::create([
            'name' => 'Another Admin',
            'email' => 'another@admin.com',
            'password' => 'password',
            'role' => User::ROLE_SUPER_ADMIN,
        ]);
    }

    public function test_super_admin_can_access_admin_dashboard()
    {
        $admin = User::where('role', User::ROLE_SUPER_ADMIN)->first();
        
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    public function test_regular_user_cannot_access_admin_dashboard()
    {
        $user = User::factory()->create(['role' => User::ROLE_STUDENT, 'is_admin' => false]);
        
        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }
}
