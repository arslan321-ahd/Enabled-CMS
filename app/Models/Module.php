<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['key', 'name'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'module_user_permissions')
            ->withPivot(['can_view', 'can_edit', 'can_delete']);
    }
}
