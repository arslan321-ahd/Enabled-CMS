<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UseCaseRequest;
use App\Models\Brand;
use App\Models\UseCase;
use App\Services\UseCaseService;
use Illuminate\Http\Request;

class UseCaseController extends Controller
{
    protected UseCaseService $useCaseService;
    public function __construct(UseCaseService $useCaseService)
    {
        $this->useCaseService = $useCaseService;
    }
    public function index(Request $request)
    {
        $statuses = $request->input('status', []);
        if (!is_array($statuses)) {
            $statuses = $statuses ? [$statuses] : [];
        }
        $statuses = array_filter($statuses, function ($status) {
            return $status !== 'all';
        });
        $query = UseCase::with('brand');
        if (!empty($statuses)) {
            $query->where(function ($q) use ($statuses) {
                $hasCondition = false;
                if (in_array('active', $statuses)) {
                    if ($hasCondition) {
                        $q->orWhere('status', 'active');
                    } else {
                        $q->where('status', 'active');
                        $hasCondition = true;
                    }
                }
                if (in_array('inactive', $statuses)) {
                    if ($hasCondition) {
                        $q->orWhere('status', 'inactive');
                    } else {
                        $q->where('status', 'inactive');
                    }
                }
            });
        }
        $useCases = $query->latest()->get();
        $brands = Brand::where('status', 'active')->get();
        return view('admin.usecases.usecases_list', compact('useCases', 'brands'));
    }
    public function store(UseCaseRequest $request)
    {
        try {
            $useCase = $this->useCaseService->store($request->validated());
            return response()->json([
                'status' => 'success',
                'message' => 'Use case created successfully.',
                'data' => $useCase
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create use case: ' . $e->getMessage()
            ], 500);
        }
    }
    public function edit(UseCase $useCase)
    {
        return response()->json([
            'status' => 'success',
            'data' => $useCase->load('brand')
        ]);
    }
    public function update(UseCaseRequest $request, UseCase $useCase)
    {
        try {
            $this->useCaseService->update($useCase, $request->validated());
            return response()->json([
                'status' => 'success',
                'message' => 'Use case updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update use case: ' . $e->getMessage()
            ], 500);
        }
    }
    public function destroy(UseCase $useCase)
    {
        try {
            $this->useCaseService->delete($useCase);
            return response()->json([
                'status' => 'success',
                'message' => 'Use case deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete use case: ' . $e->getMessage()
            ], 500);
        }
    }
}
