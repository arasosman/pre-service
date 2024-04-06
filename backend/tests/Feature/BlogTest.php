<?php

namespace Tests\Feature;

use App\Models\Blog;
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

    public function test_show_blog()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->for($user)->createQuietly();

        $this
            ->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->get("/api/v1/blogs/{$blog->id}")
            ->assertSuccessful()
            ->assertJsonPath('data.title', $blog->title)
            ->assertJsonPath('data.content', $blog->content)
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

    public function test_show_blog_with_token()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->for($user)->createQuietly();

        $token = $this->actingAs($user)
            ->post('/api/v1/tokens/create')
            ->assertSuccessful()
            ->assertJsonStructure(['token'])
            ->json('token');

        $this
            ->withToken($token)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->get("/api/v1/blogs/{$blog->id}")
            ->assertSuccessful()
            ->assertJsonPath('data.title', $blog->title)
            ->assertJsonPath('data.content', $blog->content)
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

    public function test_blog_index()
    {
        $user = User::factory()->create();

        Blog::factory()->count(5)->for($user)->createQuietly();

        $this
            ->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->get('/api/v1/blogs')
            ->assertSuccessful()
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'title',
                        'content',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    }

    public function test_update_blog()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->for($user)->createQuietly();

        $this
            ->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->putJson("/api/v1/blogs/{$blog->id}", [
                'title' => 'Updated Blog',
                'content' => 'Updated Content',
            ])
            ->assertSuccessful()
            ->assertJsonPath('data.title', 'Updated Blog')
            ->assertJsonPath('data.content', 'Updated Content')
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

    public function test_update_blog_you_dont_own()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->for($user)->createQuietly();
        $user2 = User::factory()->create();

        $this
            ->actingAs($user2)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->putJson("/api/v1/blogs/{$blog->id}", [
                'title' => 'Updated Blog',
            ])
            ->assertForbidden();
    }

    public function test_delete_blog_you_dont_own()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->for($user)->createQuietly();
        $user2 = User::factory()->create();

        $this
            ->actingAs($user2)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->delete("/api/v1/blogs/{$blog->id}")
            ->assertForbidden();
    }

    public function test_delete_blog()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->for($user)->createQuietly();

        $this
            ->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->delete("/api/v1/blogs/{$blog->id}")
            ->assertSuccessful()
            ->assertJson(['message' => 'Blog deleted successfully']);
    }
}
