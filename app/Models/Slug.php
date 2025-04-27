<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slug extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title_persian',
        'title_english',
    ];

    // --- Relationships ---

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }
}
