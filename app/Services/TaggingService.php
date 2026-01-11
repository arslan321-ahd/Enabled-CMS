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
    public function list($statuses = [])
    {
        $query = Tagging::query();
        if (!empty($statuses)) {
            $statusArray = is_array($statuses) ? $statuses : [$statuses];
            $statusArray = array_filter($statusArray, function ($status) {
                return $status !== 'all';
            });
            if (!empty($statusArray)) {
                $query->whereIn('status', $statusArray);
            }
        }
        return $query->latest()->get();
    }
}
