<?php

namespace App\Http\Controllers\Api;

use Cache;
use App\Models\Project;
use App\Models\Dangyuan;
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
        return Dangyuan::where('project_id', $project_id)->paginate(request('per_page', 15));
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
                       ->whereNotNull('audio')
                       ->paginate(request('per_page', 15));
    }

    /**
    * 七牛token
    */
    public function qiniuToken(Request $request)
    {
        $disk = QiniuStorage::disk('qiniu');
        $upload_token = $disk->uploadToken();
        $upload_domain = 'http://'.config('filesystems.disks.qiniu.domains.default');
        return ['token' => $upload_token, 'domain' => $upload_domain];
    }
}