<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Curriculum;
use App\Models\Adoption;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function analytics()
    {
        $stats = [
            'total_users'      => User::count(),
            'total_institutes' => User::where('role', 'institute')->count(),
            'total_curricula'  => Curriculum::count(),
            'total_adoptions'  => Adoption::count(),
        ];

        // Adoptions over last 7 days for chart — MongoDB-compatible date range query
        $adoptionTrends = [];
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $start = now()->subDays($i)->startOfDay();
            $end   = now()->subDays($i)->endOfDay();
            $labels[]         = now()->subDays($i)->format('M d');
            $adoptionTrends[] = Adoption::where('created_at', '>=', $start)
                ->where('created_at', '<=', $end)
                ->count();
        }

        // Unit VI: DB::table() — MongoDB raw Query Builder (not Eloquent ORM)
        // Demonstrates competency in both ORM and the raw builder pattern
        // mongodb/laravel-mongodb v5+ uses DB::table() for the raw query builder
        $avgResult = DB::table('adoptions')
            ->whereNotNull('approval_score')
            ->avg('approval_score');
        $avgApprovalScore = $avgResult ? round((float) $avgResult, 1) : 0;

        // Score ranges — computed in PHP from an Eloquent collection (ORM)
        $scores = Adoption::whereNotNull('approval_score')->pluck('approval_score');
        $scoreRanges = [
            'Needs Work (0-50)'  => $scores->filter(fn($s) => $s <= 50)->count(),
            'Good (51-75)'       => $scores->filter(fn($s) => $s > 50 && $s <= 75)->count(),
            'Excellent (76-100)' => $scores->filter(fn($s) => $s > 75)->count(),
        ];

        // Top 5 Curricula by Adoption count — manual aggregation (MongoDB-safe)
        $allCurricula = Curriculum::all();
        $topCurricula = $allCurricula->map(function ($c) {
            $c->adoptions_count = Adoption::where('curriculum_id', $c->id)->count();
            return $c;
        })->sortByDesc('adoptions_count')->take(5)->values();

        return view('admin.analytics', compact('stats', 'labels', 'adoptionTrends', 'scoreRanges', 'topCurricula', 'avgApprovalScore'));
    }
}
