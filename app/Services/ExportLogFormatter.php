<?php

namespace App\Services;

class ExportLogFormatter
{
    public static function format(string $category, array $filters): string
    {
        if (empty($filters)) {
            return ucfirst(str_replace('_', ' ', $category)) . ' exported without filters';
        }

        $filterText = collect($filters)->map(function ($value, $key) {
            return "{$key}={$value}";
        })->implode(', ');

        return ucfirst(str_replace('_', ' ', $category))
            . " exported with filters: {$filterText}";
    }
}
