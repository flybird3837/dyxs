<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CleanCache;
use App\Http\Requests\Manage\ProjectRequest;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\RoleHelper;
use Auth;
use zgldh\QiniuStorage\QiniuStorage;

class TeamController extends Controller
{
    use CleanCache, RoleHelper;
    /**
     * 项目列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $query = Team::query();

        $project_id =  $this->getUserProject()->id;

        if ($name = \request('name')) {
            $query->where('name', 'like', "%$name%");
        }

        $list = $query->where('project_id', $project_id)->orderBy('created_at', 'DESC')->paginate(\request('per_page', 15));
        return view('manage.team.index', [
            'list' => $list,
            'name' => $name
        ]);
    }

    public function addByName(Request $request){
        $team = new Team();
        $team->project_id = $this->getUserProject()->id;
        $team->name = $request->name;
        $team->save();
        return ['status' => 1];
    }

    public function editByName(Request $request){
        $team = Team::find($request->id);
        $team->name = $request->name;
        $team->save();
        return ['status' => 1];
    }

    /**
     * 删除
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function del(Request $request){
        $project_id = $this->getUserProjectId();
        $team = Team::find($request->id);
        if(!$team)
            return 1;
        if ($team->project_id != $project_id)
            return 2;
        $disk = QiniuStorage::disk('qiniu');
        $disk->delete($team->image); 
        $team->delete();
        return 0;
    }
}
