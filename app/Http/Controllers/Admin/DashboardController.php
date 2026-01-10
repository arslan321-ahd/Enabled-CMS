<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Pest\Support\View;

class DashboardController extends Controller
{
    public function index()
    {
        // Count only active users (status = 1)
        $totalBranches = User::where('status', 1)->count();

        // You can pass more data if needed
        return view('admin.dashboard', [
            'totalBranches' => $totalBranches,
            // Add other data you might need
        ]);
    }
}
