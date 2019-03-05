<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Team
 *
 */
class Team extends Model
{
    protected $fillable = [
        'name'
    ];

    protected $hidden = [
    ];

    public function getImageAttribute($value)
    {
        if ($value != null)
            return 'http://'.config('filesystems.disks.qiniu.domains.default').'/'.$value;
    }
}
