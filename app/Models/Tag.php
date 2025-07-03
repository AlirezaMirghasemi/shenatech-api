<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
    ];

    // --- Relationships (Many-to-Many using custom pivot table 'reference_tags') ---

    /**
     * رابطه چند به‌ چند با مقالات
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'reference_tags', 'tag_id', 'article_id')
                    ->withTimestamps(); // اگر جدول pivot دارای timestamps است
    }

    /**
     * رابطه چند به‌ چند با رویدادها
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'reference_tags', 'tag_id', 'event_id')
                    ->withTimestamps();
    }

    /**
     * رابطه چند به چند با ویدئوها
     */
    public function videos(): BelongsToMany
    {
        return $this->belongsToMany(Video::class, 'reference_tags', 'tag_id', 'video_id')
                    ->withTimestamps();
    }
}
