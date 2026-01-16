<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $logs = Log::with('user')
            ->latest()
            ->get();

        return view('admin.logs.index', compact('logs'));
    }
    public function show(Log $log)
    {
        // Mark as read when opened
        if (!$log->is_read) {
            $log->update(['is_read' => true]);
        }

        return view('admin.logs.show', compact('log'));
    }
    public function markRead(Log $log)
    {
        $log->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
