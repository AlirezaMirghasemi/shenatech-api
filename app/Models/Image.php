<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'type',
        'path',
        'disk',
        'mime_type',
        'size',
    ];

    protected $casts = [
        'type' => \App\Enums\ImageType::class,
    ];
}
