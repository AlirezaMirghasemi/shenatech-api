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
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
        'restored_by',
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
