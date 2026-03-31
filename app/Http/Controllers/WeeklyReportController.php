<?php

namespace App\Http\Controllers;

use App\Models\WeeklyPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WeeklyReportController extends Controller
{
    public function index()
    {
        // Group by week_start_date to get the list of weeks
        $weeks = WeeklyPlan::select(
                'week_start_date',
                'week_end_date',
                DB::raw('count(*) as total_plans'),
                DB::raw('sum(case when status = "completed" then 1 else 0 end) as completed_plans')
            )
            ->groupBy('week_start_date', 'week_end_date')
            ->orderBy('week_start_date', 'desc')
            ->get()
            ->map(function ($week) {
                $startDate = Carbon::parse($week->week_start_date);
                $endDate = Carbon::parse($week->week_end_date);

                // Calculate average KPI for the week
                $avgScore = WeeklyPlan::join('plan_scores', 'weekly_plans.id', '=', 'plan_scores.weekly_plan_id')
                    ->where('week_start_date', $week->week_start_date)
                    ->avg('final_score') ?? 0;

                return (object) [
                    'week_start_date' => $startDate,
                    'week_end_date' => $endDate,
                    'week_number' => $startDate->weekOfYear,
                    'year' => $startDate->year,
                    'total_plans' => $week->total_plans,
                    'completed_plans' => $week->completed_plans,
                    'avg_score' => $avgScore,
                ];
            });

        return view('weekly-reports.index', compact('weeks'));
    }

    public function show($week_start_date)
    {
        $startDate = Carbon::parse($week_start_date);
        $endDate = $startDate->copy()->endOfWeek();

        $plans = WeeklyPlan::with(['user', 'proofs', 'score'])
            ->where('week_start_date', $week_start_date)
            ->get();

        $stats = (object) [
            'total' => $plans->count(),
            'completed' => $plans->where('status', 'completed')->count(),
            'avg_score' => $plans->avg('score.final_score') ?? 0,
            'week_number' => $startDate->weekOfYear,
            'year' => $startDate->year,
            'range' => $startDate->translatedFormat('d F Y') . ' - ' . $endDate->translatedFormat('d F Y'),
        ];

        return view('weekly-reports.show', compact('plans', 'stats', 'week_start_date'));
    }

    public function edit(WeeklyPlan $plan)
    {
        $supervisors = \App\Models\User::where('role', 'spv')->get();
        $categories = \App\Models\Category::all();
        return view('weekly-reports.edit', compact('plan', 'supervisors', 'categories'));
    }

    public function update(Request $request, WeeklyPlan $plan)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'expected_output' => 'required|string|min:10',
            'category' => 'required|exists:categories,slug',
            'impact_level' => 'required|in:low,medium,high',
            'week_start_date' => 'required|date',
            'week_end_date' => 'required|date|after_or_equal:week_start_date',
            'notes' => 'nullable|string',
        ]);

        $plan->update($validated);

        return redirect()->route('weekly-reports.show', Carbon::parse($plan->week_start_date)->format('Y-m-d'))
            ->with('success', 'Plan updated successfully.');
    }

    public function destroy(WeeklyPlan $plan)
    {
        $weekStartDate = Carbon::parse($plan->week_start_date)->format('Y-m-d');
        $plan->delete();

        return redirect()->route('weekly-reports.show', $weekStartDate)
            ->with('success', 'Plan deleted successfully.');
    }
}
