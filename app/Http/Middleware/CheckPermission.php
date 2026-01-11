<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\PermissionHelper;

class CheckPermission
{
    public function handle($request, Closure $next, $module = null, $action = 'view')
    {
        if ($module && !auth()->user()->canAccess($module, $action)) {
            abort(403);
        }
        return $next($request);
    }
}
