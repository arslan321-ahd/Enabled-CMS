<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['name', 'slug'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'module_user_permissions')
            ->withPivot(['can_view', 'can_create', 'can_edit', 'can_delete']);
    }
}
