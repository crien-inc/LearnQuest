<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'color',
        'type',
        'requirement_value',
        'course_id',
    ];

    /**
     * Get course if badge is course-specific
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get users who earned this badge
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_badges')
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    /**
     * Check if user meets requirement for this badge
     */
    public function checkRequirement(User $user): bool
    {
        return match($this->type) {
            'course_complete' => $this->checkCourseComplete($user),
            'lessons_count' => $user->getCompletedLessonsCount() >= $this->requirement_value,
            'streak' => $user->streak_days >= $this->requirement_value,
            'points' => $user->total_points >= $this->requirement_value,
            'first_lesson' => $user->getCompletedLessonsCount() >= 1,
            'perfect_score' => $this->checkPerfectScore($user),
            default => false,
        };
    }

    /**
     * Check if user completed specific course
     */
    private function checkCourseComplete(User $user): bool
    {
        if (!$this->course_id) {
            return false;
        }
        return $user->getCourseProgress($this->course) === 100;
    }

    /**
     * Check if user got perfect score on any quiz
     */
    private function checkPerfectScore(User $user): bool
    {
        return $user->progress()
            ->whereHas('lesson', fn($q) => $q->where('type', 'quiz'))
            ->where('score', 100)
            ->exists();
    }

    /**
     * Get type label in Japanese
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'course_complete' => 'コース完了',
            'lessons_count' => 'レッスン数',
            'streak' => '連続学習',
            'points' => 'ポイント',
            'first_lesson' => '初レッスン',
            'perfect_score' => '満点',
            'special' => '特別',
            default => $this->type,
        };
    }
}
