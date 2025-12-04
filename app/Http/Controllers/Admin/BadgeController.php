<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BadgeController extends Controller
{
    /**
     * Display a listing of badges.
     */
    public function index(): Response
    {
        $badges = Badge::with('course:id,title')
            ->withCount('users')
            ->get();

        return Inertia::render('Admin/Badges/Index', [
            'badges' => $badges,
        ]);
    }

    /**
     * Show the form for creating a new badge.
     */
    public function create(): Response
    {
        $courses = Course::all(['id', 'title']);

        return Inertia::render('Admin/Badges/Create', [
            'courses' => $courses,
        ]);
    }

    /**
     * Store a newly created badge.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|max:10',
            'color' => 'required|string|max:7',
            'type' => 'required|in:course_complete,lessons_count,streak,points,first_lesson,perfect_score,special',
            'requirement_value' => 'required|integer|min:1',
            'course_id' => 'nullable|exists:courses,id',
        ]);

        Badge::create($validated);

        return redirect()->route('admin.badges.index')
            ->with('success', 'バッジを作成しました');
    }

    /**
     * Display the specified badge.
     */
    public function show(Badge $badge): Response
    {
        $badge->load(['course:id,title', 'users:id,name,email']);

        return Inertia::render('Admin/Badges/Show', [
            'badge' => $badge,
        ]);
    }

    /**
     * Show the form for editing the specified badge.
     */
    public function edit(Badge $badge): Response
    {
        $courses = Course::all(['id', 'title']);

        return Inertia::render('Admin/Badges/Edit', [
            'badge' => $badge,
            'courses' => $courses,
        ]);
    }

    /**
     * Update the specified badge.
     */
    public function update(Request $request, Badge $badge): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|max:10',
            'color' => 'required|string|max:7',
            'type' => 'required|in:course_complete,lessons_count,streak,points,first_lesson,perfect_score,special',
            'requirement_value' => 'required|integer|min:1',
            'course_id' => 'nullable|exists:courses,id',
        ]);

        $badge->update($validated);

        return redirect()->route('admin.badges.index')
            ->with('success', 'バッジを更新しました');
    }

    /**
     * Remove the specified badge.
     */
    public function destroy(Badge $badge): RedirectResponse
    {
        $badge->delete();

        return redirect()->route('admin.badges.index')
            ->with('success', 'バッジを削除しました');
    }
}
