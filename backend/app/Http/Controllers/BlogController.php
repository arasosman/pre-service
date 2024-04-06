<?php

namespace App\Http\Controllers;

use App\Http\Requests\Blog\BlogRequest;
use App\Http\Requests\Blog\BlogStoreRequest;
use App\Http\Resources\Blog\BlogResource;
use App\Http\Services\BlogService;
use App\Models\Blog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BlogRequest $request, BlogService $service): JsonResponse
    {
        Gate::authorize('viewAny', Blog::class);
        $blogs = $service->index($request);

        return BlogResource::collection($blogs)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogStoreRequest $request, BlogService $service): JsonResponse
    {
        Gate::authorize('create', Blog::class);
        $blog = $service->create($request);

        return BlogResource::make($blog)->response();
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog): JsonResponse
    {
        Gate::authorize('view', $blog);

        return BlogResource::make($blog)->response();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogRequest $request, Blog $blog, BlogService $service): JsonResponse
    {
        Gate::authorize('update', $blog);
        $blog = $service->update($request, $blog);

        return BlogResource::make($blog)->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog, BlogService $service): JsonResponse
    {
        Gate::authorize('delete', $blog);
        $service->delete($blog);

        return response()->json(['message' => 'Blog deleted successfully']);
    }
}
