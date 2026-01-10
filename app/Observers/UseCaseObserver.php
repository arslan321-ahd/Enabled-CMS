<?php

namespace App\Observers;

use App\Models\UseCase;
use App\Services\LogService;

class UseCaseObserver
{
    public function created(UseCase $useCase)
    {
        LogService::create(
            'New Use Case Created',
            "Use case '{$useCase->name}' was created under brand '{$useCase->brand->name}'",
            'created',
            $useCase
        );
    }

    public function updated(UseCase $useCase)
    {
        if ($useCase->wasChanged('status')) {
            LogService::create(
                'Use Case Status Changed',
                "Use case '{$useCase->name}' status changed to '{$useCase->status}'",
                'status_changed',
                $useCase
            );
        } else {
            LogService::create(
                'Use Case Updated',
                "Use case '{$useCase->name}' was updated",
                'updated',
                $useCase
            );
        }
    }

    public function deleted(UseCase $useCase)
    {
        LogService::create(
            'Use Case Deleted',
            "Use case '{$useCase->name}' was deleted from brand '{$useCase->brand->name}'",
            'deleted',
            $useCase
        );
    }
}
