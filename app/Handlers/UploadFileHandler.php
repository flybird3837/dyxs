<?php

namespace App\Handlers;

use App\Models\File;
use Illuminate\Http\UploadedFile;
use InvalidArgumentException;

class UploadFileHandler
{
    const LEGAL_TYPE = ['image', 'audio', 'video'];

    const ACCEPTS = [
        'image' =>  ['image/png', 'image/jpeg', 'image/gif'],
        'audio' => ['audio/mpeg', 'audio/ogg', 'audio/x-m4a', 'audio/mp3'],
        'video' => ['video/x-flv', 'video/x-m4v', 'video/mp4', 'video/x-ms-wmv']
    ];

    /**
     * 单文件上传
     *
     * @param UploadedFile $file
     * @param $type
     * @param array $options
     * @return File|bool
     */
    public function singleUpload(UploadedFile $file, $type, array $options = [])
    {
        $this->verifyFile($file, $type);
        return $this->upload($file, $type, $options);
    }


    /**
     * 多文件上传
     *
     * @param array $files
     * @param $type
     * @param array $options
     * @return array|bool
     */
    public function multiUpload(array $files, $type, array $options = [])
    {
        $result = [];

        foreach ($files as $file) {
            $this->verifyFile($file, $type);
            $response = $this->upload($file, $type, $options);

            if (!$response) {
                return false;
            }

            $result[] = $response;
        }

        return $result;
    }

    protected function verifyFile(UploadedFile $file, $type)
    {
        return true;
    }

    /**
     * 上传文件
     *
     * @param UploadedFile $file
     * @param $type
     * @param array $options
     * @return bool|File
     */
    protected function upload(UploadedFile $file, $type, array $options = [])
    {
        $savePath = null;
        $disk = null;

        $clientMineType = $file->getClientMimeType();
        $clientOriginName = $file->getClientOriginalName();
        $mineType = $file->getMimeType();
        $size = $file->getSize();

        $disk = $disk ?? config('filesystems.default');

        if (isset($options['savePath']) && $options['savePath']) {
            $savePath = $options['savePath'];
        }

        if (!$savePath) {
            $savePath = $type . '/' . date('Ymd');
        }

        if (isset($options['disk']) && $options['disk']) {
            $disk = $options['disk'];
        }

        $config = config('filesystems');

        if (!$disk) {
            $disk = $config['default'];
        }

        $diskConfig = $config['disks'][$disk];

        $uploadPath = $file->store($savePath, $options);

        $data = [
            'client_mine_type' => $clientMineType,
            'client_origin_name' => $clientOriginName,
            'mine_type' => $mineType,
            'size' => $size,
            'file_type' => $type,
            'save_path' => $uploadPath,
            'disk' => $disk
        ];

        if ($disk == 'qcloud-cos') {
            $data['region'] = $options['region'] ?? $diskConfig['region'];
            $data['bucket'] = $options['bucket'] ?? $diskConfig['default_bucket'];
        }

        $file = app(File::class);
        $file->fill($data);


        $user = auth()->user();
        if ($user->hasRole('project_manage')) {
            $file->project_id = $user->project->id;
        }

        $file->save();

        if ($uploadPath) {
            return $file;
        }

        return false;
    }
}