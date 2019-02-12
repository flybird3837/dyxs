<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Traits\CleanCache;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Xuanchuan;
use App\Http\Controllers\Traits\RoleHelper;
use zgldh\QiniuStorage\QiniuStorage;

class XuanchuanController extends Controller
{
    use RoleHelper, CleanCache;
    /**
     * @param DeviceRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Xuanchuan::query();
        $pageMap = [];
        $project_id =  $this->getUserProject()->id;

        $disk = QiniuStorage::disk('qiniu');
        $upload_token = $disk->uploadToken();
        $upload_domain = 'http://'.config('filesystems.disks.qiniu.domains.default');
        $upload_dir = 'org/'.$project_id.'/xuanchuan/';
        $files = $disk->files($upload_dir);
        foreach ($files as $file) {
            $exists = Xuanchuan::where('project_id', $project_id)
                               ->where('video', $file)->exists();
            if(!$exists){
                $xuanchuan = new Xuanchuan();
                $xuanchuan->project_id = $project_id;
                $xuanchuan->video = $file;
                $xuanchuan->save();
            }
        }

        $xuanchuans = $query->orderBy('created_at', 'DESC')
            ->where('project_id', $project_id)
            ->paginate(request('per_page', 15));

        return view('manage.xuanchuan.index', compact('xuanchuans', 'pageMap', 'upload_token', 'upload_domain', 'upload_dir'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploadToken()
    {
        $disk = QiniuStorage::disk('qiniu');
        $upload_token = $disk->uploadToken();
        $upload_domain = 'http://'.config('filesystems.disks.qiniu.domains.default');
        return ['token' => $upload_token, 'domain' => $upload_domain];
    }

    /**
     * 更新
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request){
        $project_id = $this->getUserProjectId();
        $xuanchuan = Xuanchuan::find($request->id);
        if(!$xuanchuan)
            return 1;
        if ($xuanchuan->project_id != $project_id)
            return 2;
        if($request->category)
            $xuanchuan->category = $request->category;
        $xuanchuan->name = $request->name;
        $xuanchuan->intro = $request->intro;
        $xuanchuan->save();
        return 0;
    }

    /**
     * 删除
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function del(Request $request){
        $project_id = $this->getUserProjectId();
        $xuanchuan = Xuanchuan::find($request->id);
        if(!$xuanchuan)
            return 1;
        if ($xuanchuan->project_id != $project_id)
            return 2;
        $disk = QiniuStorage::disk('qiniu');
        $disk->delete($xuanchuan->video); 
        $xuanchuan->name = $request->name;
        $xuanchuan->intro = $request->intro;
        $xuanchuan->delete();
        return 0;
    }
}
