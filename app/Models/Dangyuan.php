<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dangyuan extends Model
{
    protected $appends = ['hls_video'];

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

    public function getAudioAttribute($value)
    {
        if ($value != null)
            return 'http://'.config('filesystems.disks.qiniu.domains.default').'/'.$value;
    }

    public function getHlsVideoAttribute()
    {
        if ($this->video != null){
            $urls =  explode('/', $this->video);
            $urls[count($urls) - 1] = '/hls_'.$urls[count($urls) - 1];
            return implode('/', $urls);
        }
    }
}
