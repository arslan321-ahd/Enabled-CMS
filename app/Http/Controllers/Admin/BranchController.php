<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchRequest;
use App\Models\Module;
use App\Models\User;
use App\Services\BranchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class BranchController extends Controller
{
    protected $BranchService;
    public function __construct(BranchService $BranchService)
    {
        $this->BranchService = $BranchService;
    }

    public function index(Request $request, BranchService $branchService)
    {
        $status = $request->get('status');
        $branches = $branchService->list($status);
        $modules = Module::all();
        if ($modules->isEmpty()) {
            $defaultModules = [
                ['name' => 'Tagging', 'slug' => 'tagging'],
                ['name' => 'Announcement', 'slug' => 'announcement'],
            ];
            foreach ($defaultModules as $moduleData) {
                Module::create($moduleData);
            }
            $modules = Module::all();
        }

        return view('admin.branches.branches_list', [
            'branches' => $branches,
            'currentStatus' => $status,
            'modules' => $modules
        ]);
    }


    public function store(BranchRequest $request)
    {
        $this->BranchService->store($request->validated());

        return back()->with('status', 'branch-created');
    }
    public function update(BranchRequest $request, User $branch, BranchService $service)
    {
        try {
            $service->update($branch, $request->validated());

            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(User $branch)
    {
        try {
            $branch->delete();

            return response()->json([
                'success' => true,
                'message' => 'Branch deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to delete branch'
            ], 500);
        }
    }

    public function editPermissions(User $user)
    {
        $modules = Module::all();
        return view('admin.users.permissions', compact('user', 'modules'));
    }

    public function storePermissions(Request $request, BranchService $service)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'modules' => 'nullable|array',
            'modules.*.view'   => 'nullable|in:0,1',
            'modules.*.create' => 'nullable|in:0,1',
            'modules.*.edit'   => 'nullable|in:0,1',
            'modules.*.delete' => 'nullable|in:0,1',

        ]);

        try {
            $service->save($validated['user_id'], $validated['modules'] ?? []);

            return response()->json([
                'success' => true,
                'message' => 'Permissions updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Save Permissions Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save permissions'
            ], 500);
        }
    }
    public function getPermissions($userId)  // Change parameter from User $user to $userId
    {
        try {
            // Find user
            $user = User::find($userId);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            // Get permissions using DB query (more reliable than relationship)
            $permissions = DB::table('module_user_permissions')
                ->where('user_id', $user->id)
                ->get()
                ->mapWithKeys(function ($item) {
                    return [
                        (string) $item->module_id => [
                            'can_view'   => (bool) $item->can_view,
                            'can_create' => (bool) $item->can_create,
                            'can_edit'   => (bool) $item->can_edit,
                            'can_delete' => (bool) $item->can_delete,
                        ]
                    ];
                })
                ->toArray();

            return response()->json($permissions);
        } catch (\Exception $e) {
            Log::error('Error loading permissions: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load permissions'], 500);
        }
    }
}
