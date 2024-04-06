<?php

namespace App\Http\Controllers;

use App\Http\Requests\Blog\BlogRequest;
use App\Http\Requests\Blog\BlogStoreRequest;
use App\Http\Resources\Blog\BlogResource;
use App\Http\Services\BlogService;
use App\Models\Blog;
use Illuminate\Http\JsonResponse;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BlogRequest $request, BlogService $service): JsonResponse
    {
        $blogs = $service->index($request);

        return BlogResource::collection($blogs)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogStoreRequest $request, BlogService $service): JsonResponse
    {
        $blog = $service->create($request);

        return BlogResource::make($blog)->response();
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog): JsonResponse
    {
        return BlogResource::make($blog)->response();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogRequest $request, Blog $blog, BlogService $service): JsonResponse
    {
        $blog = $service->update($request, $blog);

        return BlogResource::make($blog)->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog, BlogService $service): JsonResponse
    {
        $service->delete($blog);

        return response()->json(['message' => 'Blog deleted successfully']);
    }
}
