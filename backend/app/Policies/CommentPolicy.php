<?php

namespace App\Policies;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\User;

/**
 * Class BlogPolicy
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class CommentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Blog $blog): bool
    {
        return $user->id === $blog->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment, Blog $blog): bool
    {
        return $user->id === $blog->user_id || $user->id === $comment->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment, Blog $blog): bool
    {
        return $blog->user_id === $user->id || $user->id === $comment->user_id;
    }
}
