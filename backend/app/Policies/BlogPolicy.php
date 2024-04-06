<?php

namespace App\Policies;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Class BlogPolicy
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class BlogPolicy
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
    public function view(User $user, Blog $blog): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Blog $blog): Response
    {
        return $user->id === $blog->user_id
            ? Response::allow()
            : Response::deny('You do not own this blog.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Blog $blog): bool
    {
        return $user->id === $blog->user_id;
    }
}
