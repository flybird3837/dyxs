<?php

namespace App\Http\Controllers\Manage;

use App\Models\User;
use App\Models\Project;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\ProjectUserRequest;


class ProjectUserController extends Controller
{
    /**
     * 添加项目管理员
     *
     * @param Project $project
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create_user($project, $role)
    {
        //echo $project.'======'.$role;
        $project = Project::query()->find($project);
        return view('manage.project-user.create_and_edit',
                         ['project' => $project, 'role' => $role, 'role_name' => $this->get_role_name($role)]);
    }

    /**
     * 添加项目管理员
     *
     * @param Project $project
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Project $project)
    {
        return view('manage.project-user.create_and_edit', ['project' => $project]);
    }

    /**
    * 获得角色名称
    */ 
    private function get_role_name($role){
        if ($role == 'administrator')
            return '维护员';
        if ($role == 'maintainer')
            return '管理员';
    }

    /**
     * 保存项目管理员
     *
     * @param ProjectUserRequest $request
     * @param Project $project
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProjectUserRequest $request, Project $project, User $user)
    {
        $user->name = $request->name ?? $project->name . '_管理员';
        $user->password = $request->password ?? '123456';
        $user->phone = $request->phone;
        $user->project_id = $project->id;
        $user->save();
        if ($request->role == 'administrator')
            $user->assignRole('project_manage');
        if ($request->role == 'maintainer')
            $user->assignRole('project_maintenance');

        return redirect()->route('projects.index')->with('success', '项目管理员添加成功');
    }

    /**
     * 编辑项目管理员
     *
     * @param Project $project
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Project $project, User $user)
    {
        if ($user->hasRole('project_manage'))
            $role = 'administrator';
        if ($user->hasRole('project_maintenance'))
            $role = 'maintainer';
        return view('manage.project-user.create_and_edit', [
            'project' => $project,
            'user' => $user,
            'role' => $role,
            'role_name' => $this->get_role_name($role)
        ]);
    }

    /**
     * 更新项目管理员
     *
     * @param ProjectUserRequest $request
     * @param Project $project
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProjectUserRequest $request, Project $project, User $user)
    {
        $user->name = $request->name;
        $user->phone = $request->phone;

        if ($request->password) {
            $user->password = $request->password;
        }

        $user->save();

        return redirect()->route('projects.index')->with('success', '项目管理员修改成功');
    }
}
