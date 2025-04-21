<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title_persian',
        'title_english',
    ];

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'reference_tags', 'tag_id', 'article_id')->withTimestamps();
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'reference_tags', 'tag_id', 'event_id')->withTimestamps();
    }

    public function videos()
    {
        return $this->belongsToMany(Video::class, 'reference_tags', 'tag_id', 'video_id')->withTimestamps();
    }
}
