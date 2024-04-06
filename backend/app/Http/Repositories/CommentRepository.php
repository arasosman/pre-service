<?php

namespace App\Http\Repositories;

use App\Models\Blog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class CommentRepository
{
    public function create(Blog $blog, array $params): Model
    {
        return $blog->comments()->create($params);
    }

    public function index(Blog $blog, array $params): LengthAwarePaginator
    {
        return $blog->comments()
            ->when(isset($params['comment']), fn ($query) => $query->where('comment', 'like', "%{$params['comment']}%"))
            ->paginate($params['per_page']);
    }
}
