<?php

namespace App\Observers;

use App\Models\Tagging;
use App\Services\LogService;

class TaggingObserver
{
    public function created(Tagging $tagging)
    {
        LogService::create(
            'New Tagging Added',
            "Tagging source '{$tagging->source}' was created",
            'created',
            $tagging
        );
    }

    public function updated(Tagging $tagging)
    {
        LogService::create(
            'Tagging Updated',
            "Tagging '{$tagging->source}' was updated",
            'updated',
            $tagging
        );
    }

    public function deleted(Tagging $tagging)
    {
        LogService::create(
            'Tagging Deleted',
            "Tagging '{$tagging->source}' was deleted",
            'deleted',
            $tagging
        );
    }
}
