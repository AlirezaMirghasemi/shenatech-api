<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use SoftDeletes;

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
        'status' => \App\Enums\ContentStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function slug()
    {
        return $this->belongsTo(Slug::class);
    }

    public function poster()
    {
        return $this->belongsTo(Image::class, 'poster_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'reference_tags', 'video_id', 'tag_id')->withTimestamps();
    }
}
