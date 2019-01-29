<?php

namespace App\Http\Controllers\Api;

use Cache;
use App\Models\Device;
use App\Models\File;
use App\Models\Map;
use App\Models\Project;
use App\Models\Site;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    public function show(Project $project, $isvip = null)
    {
        $cacheKey = 'project_data_cache_' . $project->id;

        /*$data = Cache::rememberForever($cacheKey, function() use ($project, $isvip) {
            // dump($this->formatProject($project));
            return $this->formatProject($project, $isvip);
        });*/
        return $this->formatProject($project, $isvip);
        return response()->json($data);
    }

    protected function formatProject(Project $project, $isvip)
    {
        $data = [];
        $data['name'] = $project->name;
        $maps = Map::whereProjectId($project->id)->with(['image', 'sites', 'devices'])->get();

        $mapsArr = [];

        foreach ($maps as $map) {
            $mapArr = [
                'id' => $map->id,
                'name' => $map->name,
                'width' => $map->width,
                'height' => $map->height,
                'floor' => $map->floor,
                'image' => !empty($map->image->url) ? $map->image->url : null,
                'thumb_image' => !empty($map->thumbImage->url) ? $map->thumbImage->url : null,
            ];

            $sites = $map->sites;
            $sitesArr = [];
            foreach ($sites as $site) {

                if ($isvip != null && !$site->isvip)
                    continue;
                $siteArr = [
                    'id' => $site->id,
                    'type' => $site->type,
                    'name' => $site->name,
                    'accuracy' => $site->accuracy,
                    'center_point' => $site->center_point,
                    'points' => $site->points,
                    'path' => $site->path,
                    'map_id' => $site->map_id,
                    'qrcode' => $site->qrcode,
                ];

                if ($site->images) {
                    $images = File::whereIn('id', $site->images)->get();
                    foreach ($images as $image) {
                        $siteArr['images'][] = [
                            'url' => $image->url,
                            'name' => $image->client_origin_name
                        ];
                    }
                } else {
                    $siteArr['images'] = null;
                }

                if ($site->audios) {
                    $audios = File::whereIn('id', $site->audios)->get();
                    foreach ($audios as $audio) {
                        $siteArr['audios'][] = [
                            'url' => $audio->url,
                            'name' => $audio->client_origin_name
                        ];
                    }
                } else {
                    $siteArr['audios'] = null;
                }

                if ($site->videos) {
                    $videos = File::whereIn('id', $site->videos)->get();
                    foreach ($videos as $video) {
                        $siteArr['videos'][] = [
                            'url' => $video->url,
                            'name' => $video->client_origin_name
                        ];
                    }
                } else {
                    $siteArr['videos'] = null;
                }

                $siteArr['descriptions'] = $site->descriptions;
                $sitesArr[] = $siteArr;
            }

            $mapArr['sites'] = $sitesArr;

            $devices = $map->devices;

            $devicesArr = [];
            foreach ($devices as $device) {
                $deviceArr = $device->toArray();
                unset($deviceArr['project_id']);
                unset($deviceArr['created_at']);
                unset($deviceArr['updated_at']);
                $devicesArr[] = $deviceArr;
            }

            $mapArr['devices'] = $devicesArr;
            $mapsArr[] = $mapArr;
        }

        $data['maps'] = $mapsArr;

        return $data;
    }
}
