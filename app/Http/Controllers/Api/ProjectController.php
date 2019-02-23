<?php

namespace App\Http\Controllers\Api;

use Cache;
use App\Models\Project;
use App\Models\Dangyuan;
use App\Models\Xuanchuan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use zgldh\QiniuStorage\QiniuStorage;

class ProjectController extends Controller
{
    /**
    * 党组织列表
    */
    public function list(Request $request)
    {
        return Project::all();
    }

    /**
    * 党组织党员列表
    */
    public function dangyuans(Request $request, $project_id)
    {
        return Dangyuan::where('project_id', $project_id)
                       ->whereRaw('image is null')
                       ->whereRaw('video is null')
                       ->paginate(request('per_page', 15));
    }

    /**
    * 党组织宣传片
    */
    public function xuanchuans(Request $request, $project_id, $category)
    {
        return Xuanchuan::where('project_id', $project_id)
                        ->where('category', $category)
                        ->paginate(request('per_page', 3));
    }

    /**
    * 根据名字查询党员
    */
    public function dangyuanSearch(Request $request, $project_id, $name)
    {
        return Dangyuan::where('project_id', $project_id)
                       ->where('name', 'like', '%'.$name.'%')
                       ->get();
    }

    /**
    * 根据id查询党员
    */
    public function dangyuanGet(Request $request, $project_id, $id)
    {
        return Dangyuan::where('project_id', $project_id)
                       ->where('id', $id)
                       ->first();
    }

    /**
    * 宣誓
    */
    public function dangyuanXuanshi(Request $request, $project_id)
    {
        return Dangyuan::where('project_id', $project_id)
                       ->whereNotNull('image')
                       ->whereNotNull('video')
                       ->paginate(request('per_page', 15));
    }

    /**
    * 七牛token
    */
    public function qiniuToken(Request $request)
    {
        $disk = QiniuStorage::disk('qiniu');
        $policy = [
            'callbackUrl' => 'http://xuanshi.ninewe.com/api/qiniu/callback',
            'callbackHost'=> 'xuanshi.ninewe.com',
            'callbackBody' => '{"key":"$(key)","hash":"$(etag)","w":"$(imageInfo.width)","h":"$(imageInfo.height)"}',
            'callbackBodyType' => 'application/json'
        ];
        $upload_token = $disk->uploadToken(null, 3600, $policy);
        $upload_domain = 'http://'.config('filesystems.disks.qiniu.domains.default');
        return ['token' => $upload_token, 'domain' => $upload_domain];
    }

    /**
    * 七牛回调
    */
    public function qiniuCallback(Request $request)
    {
        $disk = QiniuStorage::disk('qiniu');
        $callback = 'http://xuanshi.ninewe.com/api/qiniu/callback';
        $r = $disk->verifyCallback('application/x-www-form-urlencoded', $request->header('Authorization'), $callback, $request->getContent());//验证回调内容是否合法
        if($r){
           file_put_contents('/tmp/qiniu.log', 'OK'.PHP_EOL, FILE_APPEND);
        }
        $params = explode('/',$request->key);
        $project_id = intval($params[1]);
        $id = intval($params[3]);
        $file = $params[4];
        $dangyuan = Dangyuan::where('project_id', $project_id)
                            ->where('id', $id)
                            ->first();
        if($dangyuan){
            $pos = strpos($file, 'avatar');
            if ($pos !== false) 
                $dangyuan->image = $request->key;
            $pos = strpos($file, 'video');
            if ($pos !== false) 
                $dangyuan->video = $request->key;
            $pos = strpos($file, 'audio');
            if ($pos !== false) 
                $dangyuan->audio = $request->key;
            $dangyuan->save();
        }
        file_put_contents('/tmp/qiniu.log', $request->getContent().PHP_EOL, FILE_APPEND);
        file_put_contents('/tmp/qiniu.log', $request->header('Authorization').PHP_EOL, FILE_APPEND);
        file_put_contents('/tmp/qiniu.log', json_encode($request->all()).PHP_EOL, FILE_APPEND);
        return ["success" => true];
    }
}