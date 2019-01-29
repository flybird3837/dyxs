<?php

namespace App\Http\Middleware;

use Closure;

class DynamicConfig
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!\Auth::user()->project) {
            return $next($request);
        }

        /*
        $projectConfig = ProjectConfig::whereProjectId(\Auth::user()->project->id)->first();

        if (!$projectConfig || !$projectConfig->config) {
            return $next($request);
        }

        $dynamicConfig = $projectConfig->config;
        $filesystems = $dynamicConfig['filesystems'] ?? null;

        if ($filesystems) {
            if (!empty($filesystems['disk'])) {
                config(['filesystems.default' => $filesystems['disk']]);
            }

            if (!empty($filesystems['qcloud-cos'])) {
                config(['filesystems.disks' => array_merge(config('filesystems.disks'), ['qcloud-cos' => $filesystems['qcloud-cos']])]);
            }
        }*/

        return $next($request);
    }
}
