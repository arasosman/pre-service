<?php

namespace App\Http\Services;

use App\Http\Repositories\BlogRepository;
use App\Http\Requests\Blog\BlogRequest;
use App\Http\Requests\Blog\BlogStoreRequest;
use App\Models\Blog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

readonly class BlogService
{
    public function __construct(private BlogRepository $blogRepository)
    {
    }

    public function create(BlogStoreRequest $request): Model
    {
        return $this->blogRepository->create($request->validated());
    }

    public function index(BlogRequest $request): LengthAwarePaginator
    {
        return $this->blogRepository->index($request->validated());
    }

    public function update(BlogRequest $request, Blog $blog): Blog
    {
        $blog->update($request->validated());

        return $blog;
    }

    public function delete(Blog $blog): ?bool
    {
        return $blog->delete();
    }
}
