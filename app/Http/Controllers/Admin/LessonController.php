<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LessonController extends Controller
{
    /**
     * Display a listing of lessons for a course.
     */
    public function index(Course $course): Response
    {
        $lessons = $course->lessons()->orderBy('order')->get();

        return Inertia::render('Admin/Lessons/Index', [
            'course' => $course,
            'lessons' => $lessons,
        ]);
    }

    /**
     * Show the form for creating a new lesson.
     */
    public function create(Course $course): Response
    {
        return Inertia::render('Admin/Lessons/Create', [
            'course' => $course,
        ]);
    }

    /**
     * Store a newly created lesson.
     */
    public function store(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:video,text,quiz,assignment',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'duration_minutes' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
            'points_reward' => 'nullable|integer|min:0',
            'is_published' => 'boolean',
        ]);

        $validated['course_id'] = $course->id;
        $validated['order'] = $validated['order'] ?? $course->lessons()->max('order') + 1;
        $validated['points_reward'] = $validated['points_reward'] ?? 10;

        Lesson::create($validated);

        return redirect()->route('admin.courses.lessons.index', $course)
            ->with('success', 'レッスンを作成しました');
    }

    /**
     * Display the specified lesson.
     */
    public function show(Course $course, Lesson $lesson): Response
    {
        return Inertia::render('Admin/Lessons/Show', [
            'course' => $course,
            'lesson' => $lesson,
        ]);
    }

    /**
     * Show the form for editing the specified lesson.
     */
    public function edit(Course $course, Lesson $lesson): Response
    {
        return Inertia::render('Admin/Lessons/Edit', [
            'course' => $course,
            'lesson' => $lesson,
        ]);
    }

    /**
     * Update the specified lesson.
     */
    public function update(Request $request, Course $course, Lesson $lesson): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:video,text,quiz,assignment',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'duration_minutes' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
            'points_reward' => 'nullable|integer|min:0',
            'is_published' => 'boolean',
        ]);

        $lesson->update($validated);

        return redirect()->route('admin.courses.lessons.index', $course)
            ->with('success', 'レッスンを更新しました');
    }

    /**
     * Remove the specified lesson.
     */
    public function destroy(Course $course, Lesson $lesson): RedirectResponse
    {
        $lesson->delete();

        return redirect()->route('admin.courses.lessons.index', $course)
            ->with('success', 'レッスンを削除しました');
    }

    /**
     * Reorder lessons.
     */
    public function reorder(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'lessons' => 'required|array',
            'lessons.*.id' => 'required|exists:lessons,id',
            'lessons.*.order' => 'required|integer|min:0',
        ]);

        foreach ($validated['lessons'] as $item) {
            Lesson::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return back()->with('success', 'レッスンの順序を更新しました');
    }
}
