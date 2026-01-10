<?php

namespace App\Observers;

use App\Models\User;
use App\Services\LogService;

class UserObserver
{
    public function created(User $user)
    {
        // Branch creation (Business activity)
        LogService::create(
            'New Branch Created',
            "Branch '{$user->name}' was created",
            'created',
            $user
        );
    }

    public function updated(User $user)
    {
        // Branch status change (Business activity)
        if ($user->wasChanged('status')) {

            $status = $user->status ? 'activated' : 'deactivated';

            LogService::create(
                'Branch Status Changed',
                "Branch '{$user->name}' was {$status}",
                'status_changed',
                $user
            );

            return; // Stop further logging
        }

        // Ignore system columns to avoid noise
        $ignoredFields = ['updated_at', 'remember_token'];

        $changedFields = array_keys($user->getChanges());

        $meaningfulChanges = array_diff($changedFields, $ignoredFields);

        if (! empty($meaningfulChanges)) {

            // System log → Profile update
            LogService::create(
                'Profile Updated',
                "{$user->name} updated profile information",
                'profile_updated',
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
