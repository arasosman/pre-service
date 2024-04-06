<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\CommentRequest;
use App\Http\Requests\Comment\CommentStoreRequest;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Services\CommentService;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CommentRequest $request, Blog $blog, CommentService $service): JsonResponse
    {
        Gate::authorize('viewAny', Comment::class);
        $comments = $service->index($blog, $request);

        return CommentResource::collection($comments)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentStoreRequest $request, Blog $blog, CommentService $service): JsonResponse
    {
        Gate::authorize('create', [Comment::class, $blog]);
        $comment = $service->create($blog, $request);

        return CommentResource::make($comment)->response();
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog, Comment $comment): JsonResponse
    {
        Gate::authorize('view', [$comment, $blog]);

        return CommentResource::make($comment)->response();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentRequest $request, Blog $blog, Comment $comment, CommentService $service): JsonResponse
    {
        Gate::authorize('update', [$comment, $blog]);
        $comment = $service->update($request, $comment);

        return CommentResource::make($comment)->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog, Comment $comment, CommentService $service): JsonResponse
    {
        Gate::authorize('delete', [$comment, $blog]);
        $service->delete($comment);

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
