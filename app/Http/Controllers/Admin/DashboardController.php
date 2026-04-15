<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
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

        return view('admin.dashboard', compact(
            'totalUsers',
            'newUsersToday',
            'newUsersWeek',
            'studentCount',
            'counselorCount',
            'adminCount',
            'recentUsers',
            'monthlyData'
        ));
    }
}