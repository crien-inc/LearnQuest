<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\UserProgress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LessonController extends Controller
{
    /**
     * Display the specified lesson.
     */
    public function show(Request $request, Course $course, Lesson $lesson): Response
    {
        if (!$lesson->is_published && !$request->user()->isAdmin()) {
            abort(404);
        }

        $user = $request->user();

        // Get or create progress
        $progress = UserProgress::firstOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            ['status' => 'not_started']
        );

        // Mark as started if not already
        $progress->markAsStarted();

        // Update user streak
        $user->updateStreak();

        // Get navigation info
        $allLessons = $course->publishedLessons()->get();
        $currentIndex = $allLessons->search(fn($l) => $l->id === $lesson->id);
        $prevLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
        $nextLesson = $currentIndex < $allLessons->count() - 1 ? $allLessons[$currentIndex + 1] : null;

        return Inertia::render('Lessons/Show', [
            'course' => $course,
            'lesson' => $lesson,
            'progress' => $progress,
            'prevLesson' => $prevLesson,
            'nextLesson' => $nextLesson,
            'lessonNumber' => $currentIndex + 1,
            'totalLessons' => $allLessons->count(),
        ]);
    }

    /**
     * Update lesson progress.
     */
    public function updateProgress(Request $request, Course $course, Lesson $lesson): RedirectResponse
    {
        $validated = $request->validate([
            'progress_percentage' => 'required|integer|min:0|max:100',
        ]);

        $user = $request->user();

        $progress = UserProgress::firstOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            ['status' => 'in_progress']
        );

        $progress->updateProgress($validated['progress_percentage']);

        return back();
    }

    /**
     * Mark lesson as completed.
     */
    public function complete(Request $request, Course $course, Lesson $lesson): RedirectResponse
    {
        $user = $request->user();

        $progress = UserProgress::firstOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            ['status' => 'in_progress']
        );

        // Only award points if not already completed
        $wasCompleted = $progress->isCompleted();

        $progress->markAsCompleted($request->input('score'));

        if (!$wasCompleted) {
            // Award points
            $user->addPoints($lesson->points_reward);

            // Check for new badges
            $this->checkAndAwardBadges($user, $course);
        }

        // Get next lesson
        $nextLesson = $course->publishedLessons()
            ->where('order', '>', $lesson->order)
            ->first();

        if ($nextLesson) {
            return redirect()->route('courses.lessons.show', [$course, $nextLesson])
                ->with('success', 'レッスン完了！' . ($wasCompleted ? '' : " +{$lesson->points_reward}ポイント獲得！"));
        }

        // Check if course is completed
        $courseProgress = $user->getCourseProgress($course);
        if ($courseProgress === 100 && !$wasCompleted) {
            $user->addPoints($course->points_reward);
            return redirect()->route('courses.show', $course)
                ->with('success', "コース完了おめでとうございます！ +{$course->points_reward}ポイント獲得！");
        }

        return redirect()->route('courses.show', $course)
            ->with('success', 'レッスン完了！');
    }

    /**
     * Submit assignment.
     */
    public function submitAssignment(Request $request, Course $course, Lesson $lesson): RedirectResponse
    {
        $validated = $request->validate([
            'submission' => 'required|string|max:10000',
        ]);

        $user = $request->user();

        $progress = UserProgress::firstOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            ['status' => 'in_progress']
        );

        $progress->submission = $validated['submission'];
        $progress->save();

        return back()->with('success', '課題を提出しました！');
    }

    /**
     * Check and award badges.
     */
    private function checkAndAwardBadges($user, $course): void
    {
        $badges = Badge::whereNotIn('id', $user->badges->pluck('id'))->get();

        foreach ($badges as $badge) {
            if ($badge->checkRequirement($user)) {
                $user->badges()->attach($badge->id, ['earned_at' => now()]);
            }
        }
    }
}
