<?php

namespace App\Handlers;

use Illuminate\Support\Str;

class ImageUploadHandler{
    // 允许上传的文件后缀名
    protected $allowed_ext = ["png", "jpg", "gif", "jpeg"];

    public function save($file, $folder, $file_prefix){
        // 构建存储的文件夹规则 e.g. uploads/images/avatars/201901/10/
        $folder_name = "uploads/images/$folder/" . date("Ym/d", time());

        // 文件存储物理路径 e.g. /home/vagrant/Code/xgenebbs/public/uploads/images/avatars/201901/10/
        $upload_path = public_path() . '/' . $folder_name;

        // 获取文件后缀名
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        // 生成新的文件名
        $filename = $file_prefix . '_'. time(). '_' . Str::random(10) . '.' . $extension;

        if (! in_array($extension, $this->allowed_ext)){
            return false;
        }

        // 移动图片至目标存储路径
        $file->move($upload_path, $filename);

        return [
            'path' => config('app.url') . "/$folder_name/$filename"
        ];
    }
}