<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use App\Services\BrandService;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    protected BrandService $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    public function index(Request $request)
    {
        // Get status array from request
        $statuses = $request->input('status', []);

        // Convert to array if it's a string (for backward compatibility)
        if (!is_array($statuses)) {
            $statuses = $statuses ? [$statuses] : [];
        }

        // Remove 'all' from statuses as it means no filter
        $statuses = array_filter($statuses, function ($status) {
            return $status !== 'all';
        });

        // Query brands
        $query = Brand::query();

        if (!empty($statuses)) {
            $query->where(function ($q) use ($statuses) {
                $hasCondition = false;

                if (in_array('active', $statuses)) {
                    // Assuming brands have a 'status' field where 1 = active
                    // If your field name is different, adjust accordingly
                    $statusField = 'status'; // Change this if your field name is different

                    if ($hasCondition) {
                        $q->orWhere($statusField, 1);
                    } else {
                        $q->where($statusField, 1);
                        $hasCondition = true;
                    }
                }

                if (in_array('inactive', $statuses)) {
                    $statusField = 'status'; // Change this if your field name is different

                    if ($hasCondition) {
                        $q->orWhere($statusField, 0);
                    } else {
                        $q->where($statusField, 0);
                    }
                }
            });
        }

        // Get the filtered brands
        $brands = $query->latest()->get();

        return view('admin.brands.brands_list', compact('brands'));
    }
    public function store(BrandRequest $request)
    {
        $brand = $this->brandService->store($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Brand created successfully',
            'brand' => $brand
        ]);
    }


    public function edit(Brand $brand)
    {
        return response()->json($brand);
    }

    public function update(BrandRequest $request, Brand $brand)
    {
        try {
            $this->brandService->update($brand, $request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Brand updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update brand: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Brand $brand)
    {
        try {
            $this->brandService->delete($brand);

            return response()->json([
                'status' => 'success',
                'message' => 'Brand deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete brand: ' . $e->getMessage()
            ], 500);
        }
    }
}
