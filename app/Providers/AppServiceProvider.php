<?php

namespace App\Providers;

use App\Models\Announcement;
use App\Models\Brand;
use App\Models\Form;
use App\Models\FormField;
use App\Models\FormSubmission;
use App\Models\Log;
use App\Models\Tagging;
use App\Models\UseCase;
use App\Models\User;
use App\Observers\AnnouncementObserver;
use App\Observers\BrandObserver;
use App\Observers\FormFieldObserver;
use App\Observers\FormObserver;
use App\Observers\FormSubmissionObserver;
use App\Observers\TaggingObserver;
use App\Observers\UseCaseObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;

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
        Brand::observe(BrandObserver::class);
        UseCase::observe(UseCaseObserver::class);
        Form::observe(FormObserver::class);
        FormField::observe(FormFieldObserver::class);
        FormSubmission::observe(FormSubmissionObserver::class);
        View::composer('*', function ($view) {

            $systemActions = ['user_login', 'user_logout'];

            // All non-system notifications
            $allNotifications = Log::whereNotIn('action', $systemActions)
                ->latest()
                ->limit(15)
                ->get();

            // Unread system logs
            $systemLogs = Log::whereIn('action', $systemActions)
                ->where('is_read', false)
                ->latest()
                ->limit(10)
                ->get();

            // Total unread count (system + non-system)
            $totalUnread = Log::where('is_read', false)->count();

            $view->with([
                'allNotifications' => $allNotifications,
                'systemLogs' => $systemLogs,
                'notificationCount' => $totalUnread, // total unread for the bell icon
            ]);
        });

        Event::listen(Login::class, function ($event) {
            Log::create([
                'title' => 'User Logged In',
                'description' => $event->user->name . ' logged in',
                'action' => 'user_login',
                'loggable_type' => 'App\Models\User',
                'loggable_id' => $event->user->id,
                'user_id' => $event->user->id,
                'is_read' => true,
            ]);
        });

        // User logout
        Event::listen(Logout::class, function ($event) {
            Log::create([
                'title' => 'User Logged Out',
                'description' => $event->user->name . ' logged out',
                'action' => 'user_logout',
                'loggable_type' => 'App\Models\User',
                'loggable_id' => $event->user->id,
                'user_id' => $event->user->id,
                'is_read' => true,
            ]);
        });
    }
}
