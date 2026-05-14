<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ProfileRiasec;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
       
        $totalUsers = User::count();
        $newUsersToday = User::where('created_at', '>=', now()->subDay())->count();
        $newUsersWeek = User::where('created_at', '>=', now()->subWeek())->count();
        
     
        $studentCount = User::where('role', 'student')->count();
        $counselorCount = User::where('role', 'counselor')->count();
        $adminCount = User::where('is_admin', true)->count();
        
      
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();
        
       
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyData[] = [
                'month' => $date->format('M'),
                'count' => User::whereYear('created_at', $date->year)
                              ->whereMonth('created_at', $date->month)
                              ->count()
            ];
        }

        $recentLogs = \App\Models\AuditLog::with('user')->orderBy('created_at', 'desc')->take(6)->get();

        // --- SIAEPI v2.0 Data ---
        $totalTests = ProfileRiasec::count();
        $flaggedCount = ProfileRiasec::where('is_flagged', true)->count();
        $avgConfidence = ProfileRiasec::avg('confidence_score') ?? 0;

        $suspectProfiles = ProfileRiasec::with('user')
            ->where('is_flagged', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $codesDistribution = ProfileRiasec::selectRaw('SUBSTRING(code_holland, 1, 1) as letter, COUNT(*) as count')
            ->whereNotNull('code_holland')
            ->groupBy('letter')
            ->pluck('count', 'letter')
            ->toArray();
        
        $chartLabels = ['R', 'I', 'A', 'S', 'E', 'C'];
        $chartData = [];
        foreach ($chartLabels as $l) {
            $chartData[] = $codesDistribution[$l] ?? 0;
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'newUsersToday',
            'newUsersWeek',
            'studentCount',
            'counselorCount',
            'adminCount',
            'recentUsers',
            'monthlyData',
            'recentLogs',
            'totalTests',
            'flaggedCount',
            'avgConfidence',
            'suspectProfiles',
            'chartLabels',
            'chartData'
        ));
    }
}