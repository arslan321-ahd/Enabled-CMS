<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnnouncementRequest;
use App\Models\Announcement;
use App\Services\AnnouncementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    protected $service;
    public function __construct(AnnouncementService $service)
    {
        $this->service = $service;
    }
    public function index()
    {
        $announcements = Announcement::latest()->get();
        return view('admin.announcements.announcement_list', compact('announcements'));
    }
    public function create()
    {
        return view('admin.announcements.create_announcement');
    }
    public function store(AnnouncementRequest $request)
    {
        $this->service->store($request->validated());

        return redirect()
            ->route('admin.announcements')
            ->with('status', 'announcement-created');
    }
    public function show(Announcement $announcement)
    {
        return view('admin.announcements.show', compact('announcement'));
    }
    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }
    public function update(AnnouncementRequest $request, Announcement $announcement)
    {
        $this->service->update($announcement, $request->validated());
        return redirect()
            ->route('admin.announcements')
            ->with('status', 'announcement-updated');
    }
    public function destroy(Announcement $announcement, AnnouncementService $service)
    {
        $service->delete($announcement);
        return redirect()
            ->route('admin.announcements')
            ->with('status', 'announcement-deleted');
    }
}
