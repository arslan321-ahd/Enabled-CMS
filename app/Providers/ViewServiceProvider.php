<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\View\Composers\GreetingComposer;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('*', GreetingComposer::class);
    }
}
