<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_api_user()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/api/v1/user')
            ->assertSuccessful();
    }

    public function test_api_create_token()
    {
        $user = User::factory()->create();

        $token = $this->actingAs($user)
            ->post('/api/v1/tokens/create')
            ->assertSuccessful()
            ->assertJsonStructure(['token'])
            ->json('token');

        $this->withToken($token)
            ->get('/api/v1/user')
            ->assertSuccessful()
            ->assertJsonStructure([
                'name',
                'email',
            ]);
    }
}
