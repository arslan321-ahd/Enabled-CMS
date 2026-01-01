<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Carbon\Carbon;

class GreetingComposer
{
    public function compose(View $view): void
    {
        $hour = Carbon::now()->hour;

        if ($hour < 12) {
            $greeting = 'Good Morning';
        } elseif ($hour < 17) {
            $greeting = 'Good Afternoon';
        } elseif ($hour < 21) {
            $greeting = 'Good Evening';
        } else {
            $greeting = 'Good Night';
        }

        $view->with('greeting', $greeting);
    }
}
