<?php
namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

// Import Interfaces
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\UserServiceInterface;
use App\Interfaces\RoleRepositoryInterface;
use App\Interfaces\RoleServiceInterface;
use App\Interfaces\PermissionRepositoryInterface;
use App\Interfaces\PermissionServiceInterface;
use App\Interfaces\TagServiceInterface;
use App\Interfaces\TagRepositoryInterface;
// ... import other interfaces

// Import Implementations
use App\Repositories\UserRepository;
use App\Services\UserService;
use App\Repositories\RoleRepository;
use App\Services\RoleService;
use App\Repositories\PermissionRepository;
use App\Services\PermissionService;
use App\Services\TagService;
use App\Repositories\TagRepository;
// ... import other implementations


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind User related interfaces
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);

        // Bind Role related interfaces
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(RoleServiceInterface::class, RoleService::class);

        // Bind Permission related interfaces
            $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
            $this->app->bind(PermissionServiceInterface::class, PermissionService::class);

        // Bind Tag related interfaces
            $this->app->bind(TagRepositoryInterface::class, TagRepository::class);
            $this->app->bind(TagServiceInterface::class, TagService::class);

        // ... bind other interfaces to their implementations
        // $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
        // $this->app->bind(ArticleServiceInterface::class, ArticleService::class);

    }
    public function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
    }
}
