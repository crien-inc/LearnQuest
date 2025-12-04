<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();
        
        // Get courses with progress
        $courses = Course::published()
            ->withCount('lessons')
            ->orderBy('order')
            ->get()
            ->map(function ($course) use ($user) {
                $course->progress = $user->getCourseProgress($course);
                return $course;
            });

        // Get recent progress
        $recentProgress = $user->progress()
            ->with('lesson.course')
            ->where('status', 'completed')
            ->orderBy('completed_at', 'desc')
            ->take(5)
            ->get();

        // Get user badges
        $userBadges = $user->badges()
            ->orderBy('user_badges.earned_at', 'desc')
            ->take(6)
            ->get();

        // Get available badges not yet earned
        $availableBadges = Badge::whereNotIn('id', $user->badges->pluck('id'))
            ->take(3)
            ->get();

        // Calculate stats
        $stats = [
            'totalPoints' => $user->total_points,
            'level' => $user->level,
            'streakDays' => $user->streak_days,
            'completedLessons' => $user->getCompletedLessonsCount(),
            'earnedBadges' => $user->badges()->count(),
        ];

        return Inertia::render('Dashboard', [
            'courses' => $courses,
            'recentProgress' => $recentProgress,
            'userBadges' => $userBadges,
            'availableBadges' => $availableBadges,
            'stats' => $stats,
        ]);
    }
}
