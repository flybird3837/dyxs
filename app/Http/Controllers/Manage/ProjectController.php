<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CleanCache;
use App\Http\Requests\Manage\ProjectRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\RoleHelper;
use Auth;

class ProjectController extends Controller
{
    use CleanCache, RoleHelper;
    /**
     * 项目列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $query = Project::query();

        if ($name = \request('name')) {
            $query->where('name', 'like', "%$name%");
        }
        $list = $query->orderBy('created_at', 'DESC')->paginate(\request('per_page', 15));
        foreach ($list as $index => &$project) {
            $users = User::query()->where('project_id', '=', $project->id)->get();
            foreach ($users as $user) {
                if ($user->hasRole('project_manage'))
                    $project->project_administrator = $user;
                if ($user->hasRole('project_maintenance'))
                    $project->project_maintainer = $user;
            }
        }
        return view('manage.project.index', [
            'list' => $list,
            'name' => $name
        ]);
    }

    public function addByName(Request $request){
        $project = new Project();
        $project->name = $request->name;
        $project->save();
        return ['status' => 1];
    }

    public function editByName(Request $request){
        $project = Project::find($request->id);
        $project->name = $request->name;
        $project->save();
        return ['status' => 1];
    }

    public function show(Project $project)
    {
        $users = User::query()->where('project_id', '=', $project->id)->get();
        foreach ($users as $user) {
            if ($user->hasRole('project_manage'))
                $project->project_administrator = $user;
            if ($user->hasRole('project_maintenance'))
                $project->project_maintainer = $user;
        }

        return view('manage.project.show', ['project' => $project]);
    }

    public function create()
    {
        return view('manage.project.create_and_edit');
    }

    public function store(ProjectRequest $request, Project $project)
    {
        $project->fill($request->all());
        /*if ($user) {
            $project->user_id = $user->id;
        }*/
        $project->save();

        $user = null;
        if ($request->project_manage_phone) {
            $user = $this->createPorjectAdministrator($project, $request);
        }
        if ($request->project_maintenance_phone) {
            $user = $this->createPorjectMaintainer($project, $request);
        }

        return redirect()->route('projects.edit', Auth::user()->project_id)->with('success', '项目创建成功！');
    }

    public function edit(Project $project)
    {
        return view('manage.project.create_and_edit', ['project' => $project]);
    }

    public function update(ProjectRequest $request, Project $project)
    {
        if($request->hasFile('image')){
            $img = $request->file('image');
            // 获取后缀名
            $ext = $img->extension();
            // 使用 store 存储文件
            $path = $img->store('avatar', 'public');
            $project->image = $img->hashName();;
        }
        if ($request->password){
            Auth::user()->password = $request->password;
            Auth::user()->save(); 
        }

        $project->name = $request->name;
        $project->save();
        $this->cleanCache($project->id);

        return redirect()->route('projects.edit', $this->getUserProjectId())->with('success', '党组织修改成功');
    }
}
