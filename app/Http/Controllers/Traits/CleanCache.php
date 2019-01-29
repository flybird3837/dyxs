<?php
/**
 * Created by PhpStorm.
 * User: Caojianfei
 * Date: 2018/11/1/001
 * Time: 17:30
 */

namespace App\Http\Controllers\Traits;

use Auth;
use Cache;

trait CleanCache
{
    /**
     * 清除项目数据缓存
     *
     * @return bool
     */
    public function cleanCache($projectId = null) {

        if ($projectId) {
            $cacheKey = 'project_data_cache_' . $projectId;
            Cache::forget($cacheKey);
            return true;
        }

        if (Auth::check() && ($project = Auth::user()->project)) {
            $cacheKey = 'project_data_cache_' . $project->id;
            Cache::forget($cacheKey);
            return true;
        }

        return false;
    }

}