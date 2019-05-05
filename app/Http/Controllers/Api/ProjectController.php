<?php

namespace App\Http\Controllers\Api;

use Cache;
use App\Models\Project;
use App\Models\Dangyuan;
use App\Models\Team;
use App\Models\Xuanchuan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use zgldh\QiniuStorage\QiniuStorage;
use Illuminate\Support\Facades\DB;
use Qiniu\Processing\PersistentFop;

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
        if($request->type=='dangyuan'){
            return Dangyuan::where('project_id', $project_id)
                           ->where('id', $id)
                           ->first();
        } else if($request->type == 'team'){
            return Team::where('project_id', $project_id)
                       ->where('id', $id)
                       ->first();
        }else{
            return Dangyuan::where('project_id', $project_id)
                           ->where('id', $id)
                           ->first();
        }

    }

    /**
    * 宣誓
    */
    public function dangyuanXuanshi(Request $request, $project_id)
    {

        $dangyuans = Dangyuan::selectRaw("id, project_id, name, sex, in_time, image, video, audio, hls_id, hls_status, created_at, updated_at, 'dangyuan' as type ")
                       ->where('project_id', $project_id)
                       ->whereNotNull('image')
                       ->whereNotNull('video');
        $teams = Team::selectRaw("id, project_id, name, null as sex, null as in_time, image, video, null as audio, hls_id, hls_status, created_at, updated_at, 'team' as type ")
                       ->where('project_id', $project_id)
                       ->whereNotNull('image')
                       ->whereNotNull('video');

        $query = DB::raw("(({$teams->toSql()}) union all ({$dangyuans->toSql()})) as aa");
        $query = DB::table($query)
                 ->mergeBindings($teams->getQuery())
                 ->mergeBindings($dangyuans->getQuery());
        $results = $query->paginate(request('per_page', 15))->toArray();
        $disk = QiniuStorage::disk('qiniu');
        foreach ($results['data'] as $dangyuan) {

            if(!$dangyuan->hls_id && $dangyuan->video){
                $urls =  explode('/', $dangyuan->video);
                $urls[count($urls) - 1] = 'hls_'.$urls[count($urls) - 1];
                $hls_video = implode('/', $urls);
                $hls_video = config('filesystems.disks.qiniu.bucket').':'.$hls_video;
                $fops = 'avthumb/m3u8/segtime/10/ab/128k/ar/44100/acodec/libfaac/r/30/vb/640k/vcodec/libx264/stripmeta/0/noDomain/1|saveas/'.base64_encode($hls_video);
                $dangyuan->hls_id = $disk->persistentFop($dangyuan->video, $fops, 'dyxs_hls', true);
                if($dangyuan->type=='team')
                    $hls_dangyuan = Team::find($dangyuan->id);
                else
                    $hls_dangyuan = Dangyuan::find($dangyuan->id);
                $hls_dangyuan->hls_id = $dangyuan->hls_id;
                $hls_dangyuan->save();
            }
            if($dangyuan->video){
                $urls =  explode('/', $dangyuan->video);
                $urls[count($urls) - 1] = 'hls_'.$urls[count($urls) - 1];
                $dangyuan->hls_video = 'http://'.config('filesystems.disks.qiniu.domains.default').'/'.implode('/', $urls);

                $dangyuan->video = 'http://'.config('filesystems.disks.qiniu.domains.default').'/'.$dangyuan->video;
            }else
                $dangyuan->hls_video = null;
            if($dangyuan->image)
                $dangyuan->image = 'http://'.config('filesystems.disks.qiniu.domains.default').'/'.$dangyuan->image;
            if($dangyuan->video)
                $dangyuan->audio = 'http://'.config('filesystems.disks.qiniu.domains.default').'/'.$dangyuan->audio;

            if($dangyuan->hls_id && $dangyuan->hls_status==0){
                $result = $disk->persistentStatus($dangyuan->hls_id); 
                if(isset($result[0]['code']) && $result[0]['code'] == 0){
                    if($dangyuan->type=='team')
                        $hls_dangyuan = Team::find($dangyuan->id);
                    else
                        $hls_dangyuan = Dangyuan::find($dangyuan->id);
                    $hls_dangyuan->hls_status = 1;
                    $hls_dangyuan->save();
                }
            }
        }
        return $results;

    }

    /**
    * 集体照列表
    */
    public function teams(Request $request, $project_id)
    {
        return Team::where('project_id', $project_id)
                       ->whereRaw('image is null')
                       ->paginate(request('per_page', 15));
    }

    /**
    * 集体照获取
    */
    public function teamGet(Request $request, $project_id, $id)
    {
        return Team::where('project_id', $project_id)
                       ->where('id', $id)
                       ->first();
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
        if (strpos($request->key, 'dangyuan') !== false){
            $dangyuan = Dangyuan::where('project_id', $project_id)
                                ->where('id', $id)
                                ->first();
            if($dangyuan){
                $pos = strpos($file, 'avatar');
                if ($pos !== false) 
                    $dangyuan->image = $request->key;
                $pos = strpos($file, 'video');
                if ($pos !== false) {
                    $dangyuan->video = $request->key;
                    $hls_file = config('filesystems.disks.qiniu.bucket').':hls_'.$request->key;
                    $fops = 'avthumb/m3u8/segtime/10/ab/128k/ar/44100/acodec/libfaac/r/30/vb/640k/vcodec/libx264/stripmeta/0/noDomain/1|saveas/'.base64_encode($hls_file);
                    $dangyuan->hls_id = $disk->persistentFop($request->key, $fops, 'dyxs_hls', true); 
                }
                $pos = strpos($file, 'audio');
                if ($pos !== false) 
                    $dangyuan->audio = $request->key;
                $dangyuan->save();
            }
        } else if (strpos($request->key, 'team') !== false){
            $team = Team::where('project_id', $project_id)
                                ->where('id', $id)
                                ->first();
            if($team){
                $pos = strpos($file, 'image');
                if ($pos !== false) 
                    $team->image = $request->key;
                $pos = strpos($file, 'video');
                if ($pos !== false) {
                    $team->video = $request->key;
                    $hls_file = config('filesystems.disks.qiniu.bucket').':hls_'.$request->key;
                    $fops = 'avthumb/m3u8/segtime/10/ab/128k/ar/44100/acodec/libfaac/r/30/vb/640k/vcodec/libx264/stripmeta/0/noDomain/1|saveas/'.base64_encode($hls_file);
                    $team->hls_id = $disk->persistentFop($request->key, $fops, 'dyxs_hls', true); 
                }
                $team->save();
            }
        }
        file_put_contents('/tmp/qiniu.log', $request->getContent().PHP_EOL, FILE_APPEND);
        file_put_contents('/tmp/qiniu.log', $request->header('Authorization').PHP_EOL, FILE_APPEND);
        file_put_contents('/tmp/qiniu.log', json_encode($request->all()).PHP_EOL, FILE_APPEND);
        return ["success" => true];
    }
}