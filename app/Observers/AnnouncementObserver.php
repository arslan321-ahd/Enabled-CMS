<?php

namespace App\Observers;

use App\Models\Announcement;
use App\Services\LogService;

class AnnouncementObserver
{
    public function created(Announcement $announcement)
    {
        LogService::create(
            'New Announcement Posted',
            "Announcement '{$announcement->title}' was published in '{$announcement->category}' category",
            'created',
            $announcement
        );
    }
    public function updated(Announcement $announcement)
    {
        LogService::create(
            'Announcement Updated',
            "Announcement '{$announcement->title}' was updated",
            'updated',
            $announcement
        );
    }
    public function deleted(Announcement $announcement)
    {
        LogService::create(
            'Announcement Deleted',
            "Announcement '{$announcement->title}' was removed",
            'deleted',
            $announcement
        );
    }
}
