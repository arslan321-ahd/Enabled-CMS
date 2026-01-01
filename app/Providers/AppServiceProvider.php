<?php

namespace App\Providers;

use App\Models\Announcement;
use App\Models\Tagging;
use App\Models\User;
use App\Observers\AnnouncementObserver;
use App\Observers\TaggingObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        Tagging::observe(TaggingObserver::class);
        Announcement::observe(AnnouncementObserver::class);
    }
}
