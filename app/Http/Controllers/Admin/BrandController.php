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

    public function index()
    {
        $brands = Brand::latest()->get();
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
