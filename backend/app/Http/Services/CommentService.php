<?php

namespace App\Http\Services;

use App\Http\Repositories\CommentRepository;
use App\Http\Requests\Comment\CommentRequest;
use App\Http\Requests\Comment\CommentStoreRequest;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

readonly class CommentService
{
    public function __construct(private CommentRepository $commentRepository)
    {
    }

    public function create(Blog $blog, CommentStoreRequest $request): Model
    {
        return $this->commentRepository->create($blog, $request->validated());
    }

    public function index(Blog $blog, CommentRequest $request): LengthAwarePaginator
    {
        return $this->commentRepository->index($blog, $request->validated());
    }

    public function update(CommentRequest $request, Comment $comment): Comment
    {
        $comment->update($request->validated());

        return $comment;
    }

    public function delete(Comment $comment): ?bool
    {
        return $comment->delete();
    }
}
