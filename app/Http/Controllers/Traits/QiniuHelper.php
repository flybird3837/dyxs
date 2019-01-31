<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/18/018
 * Time: 14:50
 */

namespace App\Http\Controllers\Traits;
use zgldh\QiniuStorage\QiniuStorage;
use app\Models\Dangyuan;
use Auth;

trait QiniuHelper
{
    private static $disk;  

    public static function getDisk()  
    {  
        if (!self::$disk)  
        {  
            self::$disk = QiniuStorage::disk('qiniu');
        }  
        return self::$disk;
    }  

    public function updateFromQiniu($dangyuan)
    {
        $disk = $this->getDisk();
        if (!$dangyuan->image){
          $file = 'org/'.$dangyuan->project_id.'/dangyuan/'.$dangyuan->id.'/avatar.png';
          echo $file.'==';
          if ($disk->exists($file))
                $dangyuan->image = $file;
        }
  
        $dangyuan->save();
    }
}