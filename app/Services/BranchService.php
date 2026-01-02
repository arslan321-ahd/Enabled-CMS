<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BranchService
{
    public function store(array $data): User
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'] ?? null,
            'role'     => $data['role'],
            'status'   => $data['status'] ?? 1,
            'password' => Hash::make($data['password']),
        ]);
    }
    public function update(User $branch, array $data): User
    {
        // Check if password is provided
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            // Remove password from data array to keep old password
            unset($data['password']);
        }

        $branch->update($data);

        return $branch;
    }

    public function list($status = null)
    {
        $query = User::query();

        if ($status === 'active') {
            $query->where('status', 1);
        } elseif ($status === 'inactive') {
            $query->where('status', 0);
        } elseif ($status === 'new') {
            $query->whereDate('created_at', '>=', now()->subDays(7));
        }

        return $query->latest()->get();
    }

    public function save($userId, $modules)
    {
        foreach ($modules as $moduleId => $actions) {
            DB::table('module_user_permissions')->updateOrInsert(
                ['user_id' => $userId, 'module_id' => $moduleId],
                [
                    'can_view' => isset($actions['view']),
                    'can_edit' => isset($actions['edit']),
                    'can_delete' => isset($actions['delete']),
                ]
            );
        }
    }
}
