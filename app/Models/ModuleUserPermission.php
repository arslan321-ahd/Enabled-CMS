<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleUserPermission extends Model
{
    protected $fillable = [
        'user_id',
        'module_id',
        'can_create',
        'can_view',
        'can_edit',
        'can_delete',
    ];

    // Optional: if you want to access the related module
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    // Optional: if you want to access the related user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
