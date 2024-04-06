<?php

namespace App\Providers;

use App\Models\Blog;
use App\Models\Comment;
use App\Policies\BlogPolicy;
use App\Policies\CommentPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Blog::class, BlogPolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);
    }
}
