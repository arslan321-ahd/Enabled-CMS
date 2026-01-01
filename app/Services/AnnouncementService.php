<?php

namespace App\Services;

use App\Models\Announcement;
use Illuminate\Support\Facades\Storage;

class AnnouncementService
{
    public function store(array $data)
    {
        if (isset($data['attachment'])) {
            $data['attachment'] = $data['attachment']->store('admin/announcements/image', 'public');
        }

        return Announcement::create($data);
    }

    public function update(Announcement $announcement, array $data)
    {
        if (!empty($data['attachment'])) {
            if (
                $announcement->attachment &&
                Storage::disk('public')->exists($announcement->attachment)
            ) {
                Storage::disk('public')->delete($announcement->attachment);
            }
            $data['attachment'] = $data['attachment']
                ->store('admin/announcements/image', 'public');
        } else {
            unset($data['attachment']);
        }
        return $announcement->update($data);
    }

    public function delete(Announcement $announcement): bool
    {
        if (
            $announcement->attachment &&
            Storage::disk('public')->exists($announcement->attachment)
        ) {
            Storage::disk('public')->delete($announcement->attachment);
        }

        return $announcement->delete();
    }
}
