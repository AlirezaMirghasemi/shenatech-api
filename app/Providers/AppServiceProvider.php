<?php

namespace App\Providers;

use App\Contracts\Repositories\ArticleRepositoryInterface;
use App\Contracts\Repositories\RoleRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Services\ArticleServiceInterface;
use App\Contracts\Services\RoleServiceInterface;
use App\Contracts\Services\UserServiceInterface;
use App\Repositories\EloquentArticleRepository;
use App\Repositories\EloquentRoleRepository;
use App\Repositories\EloquentUserRepository;
use App\Services\ArticleService;
use App\Services\RoleService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // User
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);

        // Role
        $this->app->bind(RoleRepositoryInterface::class, EloquentRoleRepository::class);
        $this->app->bind(RoleServiceInterface::class, RoleService::class);

        // Article
        $this->app->bind(ArticleRepositoryInterface::class, EloquentArticleRepository::class);
        $this->app->bind(ArticleServiceInterface::class, ArticleService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
