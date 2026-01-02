<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ProcessUploadedImage implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $fullPathToOriginal,
        public string $filename,
        public string $configKey
    ) {
    }

    public function handle(): void
    {
        $config = config($this->configKey);
        $disk = $config['disk'];
        $imageData = Storage::disk($disk)->get($this->fullPathToOriginal);
        $image = Image::read($imageData);

        foreach ($config['sizes'] as $sizeName => $size) {
            $variant = clone $image;
            $variant->scale($size['width'], $size['height']);
            $path = sprintf(
                $config['reformat_path'],
                $size['width'],
                $size['height']
            );

            $fullPath = $path.'/'.$this->filename;
            $imageType = $config['format'][0];
            $encoded = $variant->encodeByExtension($imageType, $config['compression']);

            Storage::disk($disk)->put($fullPath, $encoded);
        }
    }
}
