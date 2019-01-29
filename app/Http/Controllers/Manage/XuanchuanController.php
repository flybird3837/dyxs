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

        if ($uuid = $request->uuid) {
            $query->where('uuid', $uuid);
            $pageMap['uuid'] = $uuid;
        }

        if ($name = $request->name) {
            $query->where('name', 'like', "%$name%");
            $pageMap['name'] = $name;
        }
        $xuanchuans = $query->orderBy('created_at', 'DESC')
            ->where('project_id', $this->getUserProject()->id)
            ->paginate(request('per_page', 15));
        $disk = QiniuStorage::disk('qiniu');
        $upload_token = $disk->uploadToken();
        $upload_domain = 'http://'.config('filesystems.disks.qiniu.domains.default');
        return view('manage.xuanchuan.index', compact('xuanchuans', 'pageMap', 'upload_token', 'upload_domain'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploadToken()
    {
    }
}
