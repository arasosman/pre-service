<?php

namespace App\Http\Repositories;

use App\Models\Blog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class BlogRepository
{
    public function create(array $params): Model
    {
        return Blog::query()->create($params);
    }

    public function index(array $params): LengthAwarePaginator
    {
        return Blog::query()
            ->when(isset($params['title']), function ($query) use ($params) {
                $query->where('title', 'like', '%'.$params['title'].'%');
            })
            ->when(isset($params['content']), function ($query) use ($params) {
                $query->where('content', 'like', '%'.$params['content'].'%');
            })
            ->paginate($params['per_page']);
    }
}
