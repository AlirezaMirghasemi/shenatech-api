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
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
        'restored_by',
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
