<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProgress extends Model
{
    use HasFactory;

    protected $table = 'user_progress';

    protected $fillable = [
        'user_id',
        'lesson_id',
        'status',
        'progress_percentage',
        'started_at',
        'completed_at',
        'submission',
        'score',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the lesson
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Check if completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Mark as started
     */
    public function markAsStarted(): void
    {
        if ($this->status === 'not_started') {
            $this->status = 'in_progress';
            $this->started_at = now();
            $this->save();
        }
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted(?int $score = null): void
    {
        $this->status = 'completed';
        $this->progress_percentage = 100;
        $this->completed_at = now();
        if ($score !== null) {
            $this->score = $score;
        }
        $this->save();
    }

    /**
     * Update progress
     */
    public function updateProgress(int $percentage): void
    {
        $this->progress_percentage = min(100, max(0, $percentage));
        if ($this->progress_percentage === 100) {
            $this->markAsCompleted();
        } else {
            $this->save();
        }
    }
}
