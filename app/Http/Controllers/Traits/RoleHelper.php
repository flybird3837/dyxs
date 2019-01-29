<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/18/018
 * Time: 14:50
 */

namespace App\Http\Controllers\Traits;
use App\Models\Project;
use App\Models\Map;
use App\Models\Site;

use Auth;

trait RoleHelper
{
    public function isSystemAdmin()
    {
        if (!Auth::check()) {
            abort(500, '未登录');
        }

         return Auth::user()->hasRole('administrator');
    }

    public function isProjectManage()
    {
        if (!Auth::check()) {
            abort(500, '未登录');
        }

        return Auth::user()->hasRole('project_manage') || Auth::user()->hasRole('project_maintenance');
    }

    public function getUserProject()
    {
        if (!$this->isProjectManage()) {
            abort('500', '不是项目管理员');
        }

        return Auth::user()->project;
    }

    public function getUserProjectId()
    {
        if ($this->getUserProject())
            return $this->getUserProject()->id;
        else
            return Auth::user()->project_id;
    }
}