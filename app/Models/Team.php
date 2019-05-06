<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Team
 *
 */
class Team extends Model
{
    protected $appends = ['hls_video'];

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

    public function getVideoAttribute($value)
    {
        if ($value != null)
            return 'http://'.config('filesystems.disks.qiniu.domains.default').'/'.$value;
    }

    public function getHlsVideoAttribute()
    {
        if ($this->video != null){
            $urls =  explode('/', $this->video);
            $urls[count($urls) - 1] = 'hls_'.$urls[count($urls) - 1];
            return implode('/', $urls);
        }
    }
}
