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

    /**
     * Display a listing of use cases.
     */
    public function index()
    {
        $useCases = UseCase::with('brand')->latest()->get();
        $brands = Brand::where('status', 'active')->get();

        return view('admin.usecases.usecases_list', compact('useCases', 'brands'));
    }

    /**
     * Store a newly created use case.
     */
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

    /**
     * Show the form for editing the specified use case.
     */
    public function edit(UseCase $useCase)
    {
        return response()->json([
            'status' => 'success',
            'data' => $useCase->load('brand')
        ]);
    }

    /**
     * Update the specified use case.
     */
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

    /**
     * Remove the specified use case.
     */
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
