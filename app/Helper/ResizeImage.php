<?php

namespace App\Helper;

use Image;
use Illuminate\Support\Facades\Storage;

trait ResizeImage
{
    public function resize($file){
        $path = Storage::disk('public')->putFile('adjuntos', $file);
        $file = explode('/',$file->getMimeType());
        if($file[0]  == 'image')
        {
            $image = Image::make(Storage::disk('public')->get($path));

            $image->resize(1280, null, function($constrait){
                $constrait->aspectRatio();
                $constrait->upsize();
            });

            Storage::disk('public')->put($path, (string) $image->encode('jpg', 50));
        }
        return $path;
    }
}