<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curriculum;
use App\Models\Adoption;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Activity log — role-aware
        $activities = ($user->role === 'admin')
            ? ActivityLog::latest()->limit(10)->get()
            : ActivityLog::where('user_id', $user->id)->latest()->limit(10)->get();

        $chartLabels = collect();
        $chartData = collect();
        $chartTitle = 'Analytics';

        if ($user->role === 'admin') {
            $chartTitle = 'Top Curricula by Adoptions';
            $topCurricula = Curriculum::with('adoptions')->get()->sortByDesc(fn($c) => $c->adoptions->count())->take(6);
            $chartLabels  = $topCurricula->map(fn($c) => strlen($c->title) > 18 ? substr($c->title, 0, 18).'…' : $c->title)->values();
            $chartData    = $topCurricula->map(fn($c) => $c->adoptions->count())->values();
        } elseif ($user->role === 'sme') {
            $chartTitle = 'Adoptions on My Curricula';
            $myCurricula = Curriculum::where('created_by', $user->id)->with('adoptions')->get()->sortByDesc(fn($c) => $c->adoptions->count())->take(6);
            $chartLabels  = $myCurricula->map(fn($c) => strlen($c->title) > 18 ? substr($c->title, 0, 18).'…' : $c->title)->values();
            $chartData    = $myCurricula->map(fn($c) => $c->adoptions->count())->values();
        } else {
            $chartTitle = 'My Recent Scores';
            $myAdoptions = Adoption::where('user_id', $user->id)->whereNotNull('approval_score')->latest()->limit(6)->get();
            foreach($myAdoptions as $a) {
                $c = Curriculum::find($a->curriculum_id);
                $title = $c ? $c->title : 'Unknown';
                $chartLabels->push(strlen($title) > 18 ? substr($title, 0, 18).'…' : $title);
                $chartData->push($a->approval_score);
            }
        }

        return view('dashboard', compact('activities', 'chartLabels', 'chartData', 'chartTitle'));
    }

    public function stats()
    {
        $user           = auth()->user();
        $totalCurricula = Curriculum::count();

        $today    = now()->startOfDay();
        $nextWeek = now()->addDays(7)->endOfDay();
        $upcomingDeadlines = Curriculum::where('deadline', '>=', $today)
            ->where('deadline', '<=', $nextWeek)
            ->count();

        if ($user->role === 'institute') {
            // ── Institute: show only THEIR OWN submissions & score ──────
            $myAdoptions = Adoption::where('user_id', $user->id)->get();
            $totalSubmissions = $myAdoptions->count();

            $scored   = $myAdoptions->whereNotNull('approval_score');
            $avgScore = $scored->count() > 0
                ? round($scored->avg('approval_score'), 1)
                : 0;

            $submissionsLabel = 'My Submissions';
        } elseif ($user->role === 'sme') {
            // ── SME: show only adoptions for THEIR curricula ────────────
            $myCurriculaIds = Curriculum::where('created_by', $user->id)->pluck('_id');
            $myCurriculaAdoptions = Adoption::whereIn('curriculum_id', $myCurriculaIds)->get();
            $totalSubmissions = $myCurriculaAdoptions->count();

            $scored   = $myCurriculaAdoptions->whereNotNull('approval_score');
            $avgScore = $scored->count() > 0
                ? round($scored->avg('approval_score'), 1)
                : 0;

            $submissionsLabel = 'Received Submissions';
        } else {
            // ── Admin: platform-wide figures ───────────────────────
            $totalSubmissions = Adoption::count();

            $scored   = Adoption::whereNotNull('approval_score')->get();
            $avgScore = $scored->count() > 0
                ? round($scored->avg('approval_score'), 1)
                : 0;

            $submissionsLabel = 'Total Submissions';
        }

        return response()->json([
            'totalCurricula'   => $totalCurricula,
            'totalSubmissions' => $totalSubmissions,
            'submissionsLabel' => $submissionsLabel,
            'avgScore'         => $avgScore,
            'upcomingDeadlines'=> $upcomingDeadlines,
        ]);
    }
}
