<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchRequest;
use App\Models\User;
use App\Services\BranchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

        return view('admin.branches.branches_list', [
            'branches' => $branches,
            'currentStatus' => $status
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

    public function storePermissions(Request $request, BranchService $service)
    {
        $service->save($request->user_id, $request->modules ?? []);
        return back()->with('status', 'permissions-updated');
    }
}
