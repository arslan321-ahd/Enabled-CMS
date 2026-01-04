<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'status',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function modulePermissions()
    {
        return $this->belongsToMany(Module::class, 'module_user_permissions')
            ->withPivot(['can_view', 'can_create', 'can_edit', 'can_delete']);
    }
    public function hasPermission($moduleId, $action)
    {
        $permission = $this->modulePermissions()
            ->where('module_id', $moduleId)
            ->first();

        if (!$permission) return false;

        return $permission->pivot->{"can_{$action}"} ?? false;
    }

    public function getPermissions()
    {
        return $this->modulePermissions->mapWithKeys(function ($module) {
            return [
                $module->id => [
                    'view' => (bool) $module->pivot->can_view,
                    'edit' => (bool) $module->pivot->can_edit,
                    'delete' => (bool) $module->pivot->can_delete,
                ]
            ];
        });
    }

    public function canAccess(string $moduleSlug, string $action): bool
    {
        if ($this->role === 'admin') {
            return true;
        }


        $permission = $this->modulePermissions()
            ->where('modules.slug', $moduleSlug)
            ->first();

        if (!$permission) {
            return false;
        }

        return match ($action) {
            'view'   => (bool) $permission->pivot->can_view,
            'create' => (bool) $permission->pivot->can_create,
            'edit'   => (bool) $permission->pivot->can_edit,
            'delete' => (bool) $permission->pivot->can_delete,
            default  => false,
        };
    }





    /**
     * Check if user has any permission for a module
     */
    public function hasModuleAccess($moduleSlug)
    {
        if ($this->role === 'admin') {
            return true;
        }

        $module = \App\Models\Module::where('slug', $moduleSlug)->first();

        if (!$module) {
            return false;
        }

        $permission = $this->modulePermissions()
            ->where('module_id', $module->id)
            ->first();

        return $permission !== null;
    }

    /**
     * Get all accessible modules for sidebar
     */
    public function getAccessibleModules()
    {
        if ($this->role === 'admin') {
            return \App\Models\Module::all();
        }

        return $this->modulePermissions()
            ->wherePivot('can_view', true)
            ->get();
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
