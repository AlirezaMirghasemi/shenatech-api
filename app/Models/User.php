<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, SoftDeletes;

    protected $fillable = [
        'username',
        'email',
        'first_name',
        'last_name',
        'password',
        'bio',
        'gender',
        'image_id',
        'mobile',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'gender' => \App\Enums\UserGender::class,
    ];

    // --- Relationships ---

    /**
     * رابطه کاربر با تصویر پروفایل (یک به یک معکوس)
     */
    public function profileImage(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'image_id');
    }

    /**
     * رابطه کاربر با مقالات (یک به چند)
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * رابطه کاربر با رویدادها (یک به چند)
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * رابطه کاربر با ویدئوها (یک به چند)
     */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    /**
     * رابطه کاربر با کامنت‌ها (یک به چند)
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

}
