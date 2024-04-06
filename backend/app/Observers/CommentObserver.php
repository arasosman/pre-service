<?php

namespace App\Observers;

use App\Models\Comment;

class CommentObserver
{
    /**
     * Handle the Blog "creating" event.
     */
    public function creating(Comment $comment): void
    {
        $comment->user_id = auth()->id();
    }
}
