<?php

namespace App\Services;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LogService
{
    public static function create(
        string $title,
        string $description,
        string $action,
        $model = null
    ) {
        Log::create([
            'title'         => $title,
            'description'   => $description,
            'action'        => $action,
            'loggable_type' => $model ? get_class($model) : 'excel_export',
            'loggable_id'   => $model?->id,
            'user_id'       => Auth::id(),
        ]);
    }
}
