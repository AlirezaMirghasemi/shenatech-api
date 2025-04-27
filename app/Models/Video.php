<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Enums\ContentStatus;

class Video extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'slug_id',
        'poster_id',
        'title',
        'content',
        'status',
        'url',
    ];

    protected $casts = [
        'status' => ContentStatus::class,
    ];

    // --- Relationships ---
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function slug(): BelongsTo
    {
        return $this->belongsTo(Slug::class);
    }
    public function poster(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'poster_id');
    }
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'reference_tags', 'video_id', 'tag_id')
            ->withTimestamps();
    }
}
