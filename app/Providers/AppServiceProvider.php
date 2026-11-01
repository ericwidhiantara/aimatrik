<?php

namespace App\Providers;

use App\Models\User;
use App\Services\Asset\AssetQueryService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewView;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
public function register(): void
{
    if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
        $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        $this->app->register(TelescopeServiceProvider::class);
    }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.force_https')) {
            URL::forceScheme('https');
        }

        View::composer('*', function (ViewView $view) {
            if (Auth::check()) {
                $userId = Auth::user()->id;
                // Fetch or cache user profile image
                $profileImage = Cache::remember("user:{$userId}:profileImage", 300, function () use ($userId) {
                    return (new AssetQueryService)->loadAsset(pathType: User::class, pathId: $userId);
                });
                $view->with('userProfileImage', $profileImage);
            }
        });
    }
}
