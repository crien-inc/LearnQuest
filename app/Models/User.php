<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'total_points',
        'level',
        'streak_days',
        'last_activity_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_activity_at' => 'datetime',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get user's progress records
     */
    public function progress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }

    /**
     * Get user's badges through pivot table
     */
    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    /**
     * Get user's badge records
     */
    public function userBadges(): HasMany
    {
        return $this->hasMany(UserBadge::class);
    }

    /**
     * Calculate course progress percentage
     */
    public function getCourseProgress(Course $course): int
    {
        $totalLessons = $course->lessons()->count();
        if ($totalLessons === 0) {
            return 0;
        }

        $completedLessons = $this->progress()
            ->whereIn('lesson_id', $course->lessons()->pluck('id'))
            ->where('status', 'completed')
            ->count();

        return (int) (($completedLessons / $totalLessons) * 100);
    }

    /**
     * Get total completed lessons count
     */
    public function getCompletedLessonsCount(): int
    {
        return $this->progress()->where('status', 'completed')->count();
    }

    /**
     * Add points and update level
     */
    public function addPoints(int $points): void
    {
        $this->total_points += $points;
        $this->level = $this->calculateLevel();
        $this->save();
    }

    /**
     * Calculate level based on points
     */
    private function calculateLevel(): int
    {
        $points = $this->total_points;
        if ($points < 100) return 1;
        if ($points < 300) return 2;
        if ($points < 600) return 3;
        if ($points < 1000) return 4;
        if ($points < 1500) return 5;
        if ($points < 2100) return 6;
        if ($points < 2800) return 7;
        if ($points < 3600) return 8;
        if ($points < 4500) return 9;
        return 10;
    }

    /**
     * Update streak
     */
    public function updateStreak(): void
    {
        $now = now();
        $lastActivity = $this->last_activity_at;

        if (!$lastActivity) {
            $this->streak_days = 1;
        } elseif ($lastActivity->isYesterday()) {
            $this->streak_days += 1;
        } elseif (!$lastActivity->isToday()) {
            $this->streak_days = 1;
        }

        $this->last_activity_at = $now;
        $this->save();
    }
}
