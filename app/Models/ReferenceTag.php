<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferenceTag extends Model
{
    protected $table = 'reference_tags';

    protected $fillable = [
        'tag_id',
        'article_id',
        'video_id',
        'event_id',
    ];

    public function tag()
    {
        return $this->belongsTo(Tag::class);
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
}
