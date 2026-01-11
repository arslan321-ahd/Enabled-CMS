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
            $systemActions = config('system_logs.actions'); // system-only actions

            $allNotifications = Log::latest()->limit(15)->get();
            $systemLogs      = Log::whereIn('action', $systemActions)
                ->latest()
                ->limit(10)
                ->get();
            $notificationCount = Log::count();

            $view->with([
                'allNotifications' => $allNotifications,
                'systemLogs'       => $systemLogs,
                'notificationCount' => $notificationCount
            ]);
        });
    }
}
