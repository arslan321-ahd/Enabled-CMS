<?php

namespace App\Services;

use App\Models\Module;
use App\Models\User;
use Illuminate\Support\Carbon;
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
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $branch->update($data);
        return $branch;
    }

    public function list($statuses = [])
    {
        if (!is_array($statuses)) {
            $statuses = $statuses ? [$statuses] : [];
        }
        $statuses = array_filter($statuses, function ($status) {
            return $status !== 'all';
        });
        $query = User::query();
        if (!empty($statuses)) {
            $query->where(function ($q) use ($statuses) {
                $hasCondition = false;
                if (in_array('new', $statuses)) {
                    $q->whereDate('created_at', '>=', Carbon::now()->subDays(7));
                    $hasCondition = true;
                }
                if (in_array('active', $statuses)) {
                    if ($hasCondition) {
                        $q->orWhere('status', 1);
                    } else {
                        $q->where('status', 1);
                        $hasCondition = true;
                    }
                }
                if (in_array('inactive', $statuses)) {
                    if ($hasCondition) {
                        $q->orWhere('status', 0);
                    } else {
                        $q->where('status', 0);
                    }
                }
            });
        }
        return $query->latest()->get();
    }

    public function save($userId, $modules)
    {
        DB::table('module_user_permissions')
            ->where('user_id', $userId)
            ->delete();
        if (empty($modules)) {
            return;
        }
        $permissionsToInsert = [];
        foreach ($modules as $moduleId => $actions) {
            $moduleId = (int) $moduleId;
            $hasView   = !empty($actions['view'])   && $actions['view'] == '1';
            $hasCreate = !empty($actions['create']) && $actions['create'] == '1';
            $hasEdit   = !empty($actions['edit'])   && $actions['edit'] == '1';
            $hasDelete = !empty($actions['delete']) && $actions['delete'] == '1';
            if ($hasView || $hasCreate || $hasEdit || $hasDelete) {
                $permissionsToInsert[] = [
                    'user_id'    => $userId,
                    'module_id'  => $moduleId,
                    'can_view'   => $hasView,
                    'can_create' => $hasCreate,
                    'can_edit'   => $hasEdit,
                    'can_delete' => $hasDelete,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        if (!empty($permissionsToInsert)) {
            DB::table('module_user_permissions')->insert($permissionsToInsert);
        }
    }
}
