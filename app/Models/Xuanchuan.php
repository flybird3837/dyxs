<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Xuanchuan extends Model
{
    public function getVideoAttribute($value)
    {
        if ($value != null)
            return 'http://'.config('filesystems.disks.qiniu.domains.default').'/'.$value;
    }
}
