<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Traits\CleanCache;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Xuanchuan;
use App\Http\Controllers\Traits\RoleHelper;


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

        return view('manage.xuanchuan.index', compact('xuanchuans', 'pageMap'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $sites = Auth::user()->sites;
        return view('manage.device.create_and_edit', compact('sites'));

    }

    /**
     * @param DeviceRequest $request
     * @param Device $device
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DeviceRequest $request, Device $device)
    {

        if(!Auth::user()->hasRole('project_manage'))
            return ;
        $device->fill($request->only(['site_id', 'uuid', 'name', 'x', 'y', 'z']));
        $device->project_id = $this->getUserProjectNew()->id;
        $device->save();

        $this->cleanCache();

        return redirect()->route('devices.index')->with('success', '设备添加成功');
    }

    /**
     * @param Device $device
     */
    public function show(Device $device)
    {
        // dump($device);
        // dump($device->site);
    }

    /**
     * @param Device $device
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Device $device)
    {
        $sites = Auth::user()->sites;
        return view('manage.device.create_and_edit', compact('sites', 'device'));
    }

    /**
     * @param Device $device
     * @param DeviceRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Device $device, DeviceRequest $request)
    {
        if(!Auth::user()->hasRole('project_manage'))
            return ;
        $device->update($request->only(['site_id', 'uuid', 'name', 'x', 'y', 'z']));

        $this->cleanCache();

        return redirect()->route('devices.index')->with('success', '设备修改成功');
    }

    /**
     * @param Device $device
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Device $device)
    {
        if(!Auth::user()->hasRole('project_manage'))
            return ;
        $device->delete();

        $this->cleanCache();

        return redirect()->route('devices.index')->with('success', '设备删除成功');
    }
}
