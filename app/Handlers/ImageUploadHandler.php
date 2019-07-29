<?php

namespace App\Handlers;

use function foo\func;
use Illuminate\Support\Str;
use Image;

class ImageUploadHandler{
    // 允许上传的文件后缀名
    protected $allowed_ext = ["png", "jpg", "gif", "jpeg"];

    public function save($file, $folder, $file_prefix, $max_width=false){
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

        // 限制图片宽带，裁剪图片
        if($max_width && $extension != 'gif'){
            $this->reduceSize($upload_path . '/' . $filename, $max_width);
        }

        return [
            'path' => config('app.url') . "/$folder_name/$filename"
        ];
    }

    public function reduceSize($file_path, $max_width){
        $image = Image::make($file_path);

        // 调整图片大小
        $image->resize($max_width, null, function ($constraint){
            // 设定宽度是 $max_width，高度等比例双方缩放
            $constraint->aspectRatio();

            // 防止裁剪时图片尺寸变大
            $constraint->upsize();
        });

        $image->save();
    }
}