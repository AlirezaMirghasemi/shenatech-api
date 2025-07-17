<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends SpatiePermission
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'guard_name',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

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
