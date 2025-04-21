<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'article_id',
        'video_id',
        'event_id',
        'parent_id',
        'content',
        'status',
    ];

    protected $casts = [
        'status' => \App\Enums\CommentStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
