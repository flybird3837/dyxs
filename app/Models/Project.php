<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Project
 *
 */
class Project extends Model
{
    protected $fillable = [
        'name'
    ];

    protected $hidden = [
    ];

    public function getImageAttribute($value)
    {
        if ($value != null)
            return config('filesystems.disks.public.url').'/avatar/'.$value;
    }
}
