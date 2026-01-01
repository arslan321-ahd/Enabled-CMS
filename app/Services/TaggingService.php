<?php

namespace App\Services;

use App\Models\Tagging;

class TaggingService
{
    public function store(array $data): Tagging
    {
        return Tagging::create($data);
    }

    public function update(Tagging $tagging, array $data): Tagging
    {
        $tagging->update($data);
        return $tagging;
    }

    public function delete(Tagging $tagging): bool
    {
        return $tagging->delete();
    }

    public function list($status = null)
    {
        $query = Tagging::query();

        if ($status && in_array($status, ['online', 'offline'])) {
            $query->where('status', $status);
        }

        return $query->latest()->get();
    }
}
