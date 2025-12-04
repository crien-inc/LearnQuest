<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    /**
     * Display a listing of courses.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();

        $courses = Course::published()
            ->withCount('lessons')
            ->orderBy('order')
            ->get()
            ->map(function ($course) use ($user) {
                $course->progress = $user->getCourseProgress($course);
                $course->formatted_duration = $course->formatted_duration;
                return $course;
            });

        return Inertia::render('Courses/Index', [
            'courses' => $courses,
        ]);
    }

    /**
     * Display the specified course.
     */
    public function show(Request $request, Course $course): Response
    {
        if (!$course->is_published && !$request->user()->isAdmin()) {
            abort(404);
        }

        $user = $request->user();

        $lessons = $course->publishedLessons()
            ->get()
            ->map(function ($lesson) use ($user) {
                $progress = $user->progress()->where('lesson_id', $lesson->id)->first();
                $lesson->user_progress = $progress ? [
                    'status' => $progress->status,
                    'progress_percentage' => $progress->progress_percentage,
                    'completed_at' => $progress->completed_at,
                ] : null;
                return $lesson;
            });

        $courseProgress = $user->getCourseProgress($course);

        return Inertia::render('Courses/Show', [
            'course' => $course,
            'lessons' => $lessons,
            'courseProgress' => $courseProgress,
        ]);
    }
}
