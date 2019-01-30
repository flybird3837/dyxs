<?php

namespace App\Http\Controllers\Api;

use Cache;
use App\Models\Project;
use App\Models\Dangyuan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        return Dangyuan::where('project_id', $project_id)->get();
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
}