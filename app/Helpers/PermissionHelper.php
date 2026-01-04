<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermissionHelper
{
    public static function can(string $moduleSlug, string $action): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        // Optional: super admin bypass
        if ($user->is_admin ?? false) {
            return true;
        }

        return DB::table('module_user_permissions')
            ->join('modules', 'modules.id', '=', 'module_user_permissions.module_id')
            ->where('module_user_permissions.user_id', $user->id)
            ->where('modules.slug', $moduleSlug)
            ->where("module_user_permissions.can_{$action}", true)
            ->exists();
    }
}
