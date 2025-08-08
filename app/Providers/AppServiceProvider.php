<?php
namespace App\Providers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use App\Observers\PermissionObserver;
use App\Observers\RoleObserver;
use App\Observers\TagObserver;
use App\Observers\UserObserver;
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
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5) // حداکثر 5 درخواست در دقیقه
                ->by($request->input('email') . '|' . $request->ip()) // ترکیب ایمیل و IP
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'message' => 'Too many login attempts. Please try again later.'
                    ], 429, $headers);
                });
        });
    }
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
        Permission::observe(PermissionObserver::class);
        Role::observe(RoleObserver::class);
        User::observe(UserObserver::class);
        Tag::observe(TagObserver::class);
    }
}
