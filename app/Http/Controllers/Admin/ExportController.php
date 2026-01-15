<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Tagging;
use App\Models\UseCase;
use App\Models\Form;
use App\Models\User;
use App\Exports\BrandsExport;
use App\Exports\TaggingsExport;
use App\Exports\UseCasesExport;
use App\Exports\AllFormsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('name')->get(['id', 'name']);
        $forms = Form::with('user:id,name')
            ->orderBy('title')
            ->get(['id', 'title', 'user_id', 'active']);
        $branches = User::orderBy('name')->get(['id', 'name']);

        return view('admin.export.index', compact('brands', 'forms', 'branches'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'category' => 'required|in:brands,taggings,use_cases,customer_list',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $category = $request->category;
        $filters = [];

        // Common filters
        if ($request->filled('start_date')) {
            $filters['start_date'] = $request->start_date;
        }
        if ($request->filled('end_date')) {
            $filters['end_date'] = $request->end_date;
        }

        // Category-specific filters
        switch ($category) {
            case 'brands':
                if ($request->filled('status')) {
                    $filters['status'] = $request->status;
                }
                break;

            case 'taggings':
                if ($request->filled('status')) {
                    $filters['status'] = $request->status;
                }
                break;

            case 'use_cases':
                if ($request->filled('brand_id')) {
                    $filters['brand_id'] = $request->brand_id;
                }
                if ($request->filled('status')) {
                    $filters['status'] = $request->status;
                }
                break;

            case 'customer_list':
                if ($request->filled('form_id')) {
                    $filters['form_id'] = $request->form_id;
                }
                break;
        }

        $timestamp = now()->format('Y-m-d_H-i-s');

        // Export logic
        switch ($category) {
            case 'brands':
                $filename = "brands_export_{$timestamp}.xlsx";
                return Excel::download(new BrandsExport($filters), $filename);

            case 'taggings':
                $filename = "taggings_export_{$timestamp}.xlsx";
                return Excel::download(new TaggingsExport($filters), $filename);

            case 'use_cases':
                $filename = "use_cases_export_{$timestamp}.xlsx";
                return Excel::download(new UseCasesExport($filters), $filename);

            case 'customer_list':
                $filename = "customer_list_export_{$timestamp}.xlsx";
                return Excel::download(new AllFormsExport($filters), $filename);

            default:
                return back()->with('error', 'Invalid export category selected.');
        }
    }

    public function getBrandByUseCase(UseCase $useCase)
    {
        return response()->json([
            'id'   => $useCase->brand->id,
            'name' => $useCase->brand->name,
        ]);
    }
}
