<?php

namespace App\Models;

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
        'gender' => \App\Enums\UserGender::class,
    ];

    public function profileImage()
    {
        return $this->belongsTo(Image::class, 'image_id');
    }

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

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
