<?php

namespace App\Observers;

use App\Models\User;
use App\Services\LogService;

class UserObserver
{
    public function created(User $user)
    {
        LogService::create(
            'New Branch Created',
            "Branch '{$user->name}' was created",
            'created',
            $user
        );
    }

    public function updated(User $user)
    {
        if ($user->wasChanged('status')) {
            $status = $user->status ? 'activated' : 'deactivated';

            LogService::create(
                'Branch Status Changed',
                "Branch '{$user->name}' was {$status}",
                'status_changed',
                $user
            );
        }
    }

    public function deleted(User $user)
    {
        LogService::create(
            'Branch Deleted',
            "Branch '{$user->name}' was deleted",
            'deleted',
            $user
        );
    }
}
