<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaggingRequest;
use App\Models\Tagging;
use App\Services\TaggingService;
use Illuminate\Http\Request;

class TaggingController extends Controller
{
    protected $taggingService;

    public function __construct(TaggingService $taggingService)
    {
        $this->taggingService = $taggingService;
    }
    public function index(Request $request, TaggingService $taggingService)
    {
        $statuses = $request->get('status', []);
        $taggings = $taggingService->list($statuses);
        $currentStatuses = is_array($statuses) ? $statuses : ($statuses ? [$statuses] : []);
        return view('admin.tagging.tagging_list', compact('taggings', 'currentStatuses'));
    }


    public function store(TaggingRequest $request)
    {
        $this->taggingService->store($request->validated());

        return redirect()->back()->with('status', 'tagging-created');
    }

    public function update(TaggingRequest $request, Tagging $tagging, TaggingService $taggingService)
    {
        $taggingService->update($tagging, $request->validated());
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tagging updated successfully',
                'data' => $tagging
            ]);
        }
        return redirect()->back()->with('status', 'tagging-updated');
    }

    public function destroy(Tagging $tagging, TaggingService $taggingService)
    {
        $taggingService->delete($tagging);

        return redirect()->back()->with('status', 'tagging-deleted');
    }
}
