<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot; // Extend Pivot for pivot models

class ReferenceTag extends Pivot
{
    protected $table = 'reference_tags';
    // public $timestamps = true; // if you need to access them directly
}
