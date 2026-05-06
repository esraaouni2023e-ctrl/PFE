<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\TestAttempt;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index()
    {
        // Get latest audit logs
        $logs = AuditLog::with('user')->orderBy('created_at', 'desc')->paginate(50);

        // Calculate performance statistics (mock AI/platform performance since we aren't using actual AI)
        // 1. Total test completed
        $totalTests = TestAttempt::count();
        
        // 2. Average test score (Platform precision proxy)
        $averageScore = TestAttempt::avg('score') ?? 0;

        // 3. User engagement
        $activeStudents = User::where('role', 'student')
            ->whereHas('profile', function($query) {
                $query->whereNotNull('status');
            })->count();

        $stats = [
            'total_tests' => $totalTests,
            'average_score' => round($averageScore, 1),
            'active_students' => $activeStudents,
        ];

        return view('admin.audit', compact('logs', 'stats'));
    }
}
