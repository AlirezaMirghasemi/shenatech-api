<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Enums\ContentStatus; // Import Enum

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'slug_id',
        'poster_id',
        'title',
        'content',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
        'restored_by',
    ];

    protected $casts = [
        'status' => ContentStatus::class, // کست کردن به Enum
    ];

    // --- Relationships ---

    /**
     * نویسنده مقاله (رابطه یک به یک معکوس)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * اسلاگ مقاله (رابطه یک به یک معکوس)
     */
    public function slug(): BelongsTo
    {
        return $this->belongsTo(Slug::class);
    }

    /**
     * تصویر پوستر مقاله (رابطه یک به یک معکوس)
     */
    public function poster(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'poster_id');
    }

    /**
     * کامنت‌های مقاله (رابطه یک به چند)
     */
    public function comments(): HasMany
    {
         return $this->hasMany(Comment::class);
    }

    /**
     * تگ‌های مقاله (رابطه چند به چند)
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'reference_tags', 'article_id', 'tag_id')
                    ->withTimestamps();
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function restorer()
    {
        return $this->belongsTo(User::class, 'restored_by');
    }
    public function destroyer()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
