<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\PortalNotification;

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
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        View::composer('*', function ($view) {
            $unreadGlobalCount = 0;
            if (Auth::check()) {
                $unreadGlobalCount = PortalNotification::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->count();
            }
            $view->with('unreadGlobalCount', $unreadGlobalCount);
        });
    }
}
