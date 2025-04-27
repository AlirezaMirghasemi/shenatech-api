<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\CommentStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'article_id',
        'video_id',
        'event_id',
        'parent_id', // برای پاسخ‌ها
        'content',
        'status',
    ];

    protected $casts = [
        'status' => CommentStatus::class,
    ];

    // --- Relationships ---

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    protected function commentable(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->article ?? $this->video ?? $this->event,
        );
    }

    protected function commentableType(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->article_id) return 'article';
                if ($this->video_id) return 'video';
                if ($this->event_id) return 'event';
                return null;
            }
        );
    }
}
