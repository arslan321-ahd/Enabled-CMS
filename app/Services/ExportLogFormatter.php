<?php

namespace App\Services;

class ExportLogFormatter
{
    public static function format(string $category, array $filters): string
    {
        // Remove technical filters
        unset($filters['start_date'], $filters['end_date']);

        // Remove filters that mean "all"
        $filters = collect($filters)
            ->reject(fn($value) => $value === 'all' || $value === null || $value === '')
            ->toArray();

        $categoryName = ucfirst(str_replace('_', ' ', $category));

        if (empty($filters)) {
            return "{$categoryName} exported";
        }

        $filterText = collect($filters)
            ->map(fn($value, $key) => "{$key}={$value}")
            ->implode(', ');

        return "{$categoryName} exported with filters: {$filterText}";
    }
}
