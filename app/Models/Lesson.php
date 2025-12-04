<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'type',
        'content',
        'video_url',
        'duration_minutes',
        'order',
        'points_reward',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    /**
     * Get the course this lesson belongs to
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get progress records for this lesson
     */
    public function progress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }

    /**
     * Scope for published lessons
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Get type label in Japanese
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'video' => '動画',
            'text' => 'テキスト',
            'quiz' => 'クイズ',
            'assignment' => '課題',
            default => $this->type,
        };
    }

    /**
     * Get type icon
     */
    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'video' => '🎬',
            'text' => '📄',
            'quiz' => '❓',
            'assignment' => '📝',
            default => '📚',
        };
    }
}
