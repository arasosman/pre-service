<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_comment()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->for($user)->createQuietly();

        $this
            ->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->postJson("/api/v1/blogs/{$blog->id}/comments", [
                'comment' => 'Test Comment',
            ])
            ->assertCreated()
            ->assertJsonPath('data.comment', 'Test Comment')
            ->assertJsonPath('data.blog_id', $blog->id)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'blog_id',
                    'comment',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    public function test_create_comment_doesnt_own_blog()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->for($user)->createQuietly();

        $user2 = User::factory()->create();

        $this
            ->actingAs($user2)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->postJson("/api/v1/blogs/{$blog->id}/comments", [
                'comment' => 'Test Comment',
            ])
            ->assertForbidden();

    }

    public function test_show_comment()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->for($user)->createQuietly();
        $comment = Comment::factory()->for($blog)->for($user)->createQuietly();

        $this
            ->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->get("/api/v1/blogs/{$blog->id}/comments/{$comment->id}")
            ->assertSuccessful()
            ->assertJsonPath('data.comment', $comment->comment)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'user_id',
                    'comment',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    public function test_show_comments()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->for($user)->createQuietly();
        Comment::factory()->for($blog)->for($user)->count(3)->createQuietly();

        $this
            ->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->get("/api/v1/blogs/{$blog->id}/comments")
            ->assertSuccessful()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'comment',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    }

    public function test_show_comments_doesnt_own_blog()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->for($user)->createQuietly();
        Comment::factory()->for($blog)->for($user)->count(3)->createQuietly();

        $user2 = User::factory()->create();

        $this
            ->actingAs($user2)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->get("/api/v1/blogs/{$blog->id}/comments")
            ->assertSuccessful();
    }

    public function test_update_comment()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->for($user)->createQuietly();
        $comment = Comment::factory()->for($blog)->for($user)->createQuietly();

        $this
            ->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->putJson("/api/v1/blogs/{$blog->id}/comments/{$comment->id}", [
                'comment' => 'Updated Comment',
            ])
            ->assertSuccessful()
            ->assertJsonPath('data.comment', 'Updated Comment')
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'user_id',
                    'comment',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    public function test_update_comment_doesnt_own_blog()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->for($user)->createQuietly();
        $comment = Comment::factory()->for($blog)->for($user)->createQuietly();

        $user2 = User::factory()->create();

        $this
            ->actingAs($user2)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->putJson("/api/v1/blogs/{$blog->id}/comments/{$comment->id}", [
                'comment' => 'Updated Comment',
            ])
            ->assertForbidden();
    }

    public function test_update_comment_by_own_comment()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $blog = Blog::factory()->for($user)->createQuietly();
        Comment::factory()->for($blog)->for($user)->createQuietly();
        $comment = Comment::factory()->for($blog)->for($user2)->createQuietly();

        $this
            ->actingAs($user2)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->putJson("/api/v1/blogs/{$blog->id}/comments/{$comment->id}", [
                'comment' => 'Updated Comment',
            ])
            ->assertSuccessful();
    }

    public function test_delete_comment()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->for($user)->createQuietly();
        $comment = Comment::factory()->for($blog)->for($user)->createQuietly();

        $this
            ->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->delete("/api/v1/blogs/{$blog->id}/comments/{$comment->id}")
            ->assertSuccessful()
            ->assertJson(['message' => 'Comment deleted successfully']);
    }

    public function test_delete_comment_blog_owner()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $blog = Blog::factory()->for($user)->createQuietly();
        $comment = Comment::factory()->for($blog)->for($user2)->createQuietly();

        $this
            ->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->delete("/api/v1/blogs/{$blog->id}/comments/{$comment->id}")
            ->assertSuccessful()
            ->assertJson(['message' => 'Comment deleted successfully']);
    }
}
