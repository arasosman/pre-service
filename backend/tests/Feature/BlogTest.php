<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_blog_unauthorized(): void
    {
        $this
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->get('/api/v1/blogs')
            ->assertUnauthorized();
    }

    public function test_create_blog()
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->postJson('/api/v1/blogs', [
                'title' => 'Test Blog',
                'content' => 'Test Content',
            ])
            ->assertCreated()
            ->assertJsonPath('data.title', 'Test Blog')
            ->assertJsonPath('data.content', 'Test Content')
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'user_id',
                    'title',
                    'content',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }
}
