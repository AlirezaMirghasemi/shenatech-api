<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\ImageType; // Import Enum
use Illuminate\Support\Facades\Storage; // برای تولید URL
use Illuminate\Database\Eloquent\Casts\Attribute; // برای Accessor/Mutator مدرن

class Image extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'type',
        'path',
        'disk',
        'mime_type',
        'size',
    ];

    protected $casts = [
        'type' => ImageType::class, // کست کردن به Enum
    ];

    /**
     * Accessor برای دریافت URL کامل تصویر
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->path ? asset('storage/' . $this->path) : null,
        );
    }
}
