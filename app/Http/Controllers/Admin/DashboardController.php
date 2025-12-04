<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): Response
    {
        $stats = [
            'totalUsers' => User::where('role', 'user')->count(),
            'totalCourses' => Course::count(),
            'totalLessons' => Lesson::count(),
            'totalBadges' => Badge::count(),
            'completedLessons' => UserProgress::where('status', 'completed')->count(),
            'activeUsers' => User::where('last_activity_at', '>=', now()->subDays(7))->count(),
        ];

        $recentUsers = User::orderBy('created_at', 'desc')
            ->take(5)
            ->get(['id', 'name', 'email', 'created_at', 'total_points', 'level']);

        $recentProgress = UserProgress::with(['user:id,name', 'lesson:id,title'])
            ->where('status', 'completed')
            ->orderBy('completed_at', 'desc')
            ->take(10)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'recentProgress' => $recentProgress,
        ]);
    }
}
