<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\FormSubmission;
use App\Models\Form;
use App\Models\Log as ModelsLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBranches = User::where('status', 1)->count();

        // Total forms (customers)
        $totalForms = Form::count();

        // Total form submissions
        $totalSubmissions = FormSubmission::count();

        // Today's submissions
        $todaySubmissions = FormSubmission::whereDate('created_at', today())->count();

        // Debug: Check if we have any submissions at all
        $allSubmissions = FormSubmission::count();
        \Log::info('Total submissions in system: ' . $allSubmissions);

        // Chart 1: Monthly Submissions (Last 6 months)
        $monthlySubmissions = $this->getMonthlySubmissions(6);

        // Chart 2: Top Forms by Submissions (Last 30 days)
        $topForms = $this->getTopFormsBySubmissions(30);

        // Debug output
        Log::info('Monthly Submissions Data:', $monthlySubmissions);
        Log::info('Top Forms Data:', $topForms);

        // Latest logs for dashboard timeline
        $logs = ModelsLog::latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalBranches',
            'totalForms',
            'totalSubmissions',
            'todaySubmissions',
            'monthlySubmissions',
            'topForms',
            'logs'
        ));
    }

    /**
     * Get monthly submissions data for chart
     */
    /**
     * Get monthly submissions data for chart
     */
    private function getMonthlySubmissions($months = 6)
    {
        $data = [];
        $labels = [];

        Log::info('Getting monthly submissions for ' . $months . ' months');

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('M Y');

            Log::info('Checking submissions for: ' . $month);

            $submissionCount = FormSubmission::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            Log::info('Found ' . $submissionCount . ' submissions for ' . $month);

            $labels[] = $date->format('M');
            $data[] = $submissionCount;
        }

        Log::info('Final monthly data:', ['labels' => $labels, 'data' => $data]);

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Get top forms by submissions
     */
    private function getTopFormsBySubmissions($days = 30)
    {
        $startDate = Carbon::now()->subDays($days);

        Log::info('Getting top forms since: ' . $startDate);

        $topForms = Form::select(
            'forms.id',
            'forms.title',
            DB::raw('COUNT(form_submissions.id) as submission_count')
        )
            ->leftJoin('form_submissions', function ($join) use ($startDate) {
                $join->on('forms.id', '=', 'form_submissions.form_id')
                    ->where('form_submissions.created_at', '>=', $startDate);
            })
            ->groupBy('forms.id', 'forms.title')
            ->orderBy('submission_count', 'DESC')
            ->limit(8)
            ->get();

        Log::info('Top forms query result:', $topForms->toArray());

        $formNames = [];
        $submissionCounts = [];

        foreach ($topForms as $form) {
            $formNames[] = strlen($form->title) > 15 ? substr($form->title, 0, 12) . '...' : $form->title;
            $submissionCounts[] = (int)$form->submission_count;
        }

        // If no forms have submissions, still show forms with zero
        if (empty($formNames)) {
            $allForms = Form::limit(6)->get();
            foreach ($allForms as $form) {
                $formNames[] = strlen($form->title) > 15 ? substr($form->title, 0, 12) . '...' : $form->title;
                $submissionCounts[] = 0;
            }
        }

        Log::info('Final top forms data:', ['names' => $formNames, 'counts' => $submissionCounts]);

        return [
            'form_names' => $formNames,
            'submission_counts' => $submissionCounts
        ];
    }
}
