<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

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
         // Share notifications data with the notification component
        // This makes notifications available to your notification component globally
        View::composer('components.notification', function ($view) {
            $notifications = collect();
            $unreadCount = 0;
            
            if (Auth::check()) {
                // Get latest 10 notifications for the authenticated user
                $notifications = Notification::where('user_id', Auth::id())
                    ->orderByRaw('read_at IS NULL DESC') // unread first
                    ->latest()
                    ->take(10)
                    ->get();
                
                // Count unread notifications
                $unreadCount = $notifications->where('read_at', null)->count();
            }
            
            // Pass data to the component
            $view->with(compact('notifications', 'unreadCount'));
        });
    }
}
