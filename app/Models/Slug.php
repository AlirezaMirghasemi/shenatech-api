<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slug extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title_persian',
        'title_english',
    ];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }
}
