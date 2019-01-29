<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/15/015
 * Time: 16:23
 */

namespace App\Http\Transformers;

use App\Models\File;
use League\Fractal\TransformerAbstract;

class FileTransformer extends TransformerAbstract
{
    public function transform(File $file)
    {
        $data = [
            'id' => $file->id,
            'client_mine_type' => $file->client_mine_type,
            'client_origin_name' => $file->client_origin_name,
            'mine_type' => $file->mine_type,
            'size' => $file->size,
            'save_path' => $file->save_path,
            'file_type' =>$file->file_type,
            'disk' => $file->disk,
            'url' => $file->url,
        ];

        if ($file->disk == 'qcloud') {
            $data['region'] = $file->region;
            $data['bucket'] = $file->bucket;
        }

        $data['created_at'] = $file->created_at->toDateTimeString();
        $data['updated_at'] = $file->updated_at->toDateTimeString();

        return $data;
    }
}