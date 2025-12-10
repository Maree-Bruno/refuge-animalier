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
        public string $newOriginalFileName,
        public string $configKey
    ) {}

    public function handle(): void
    {
        $config = config($this->configKey);
        $disk = Storage::disk($config['disk']);

        $image = Image::read($disk->get($this->fullPathToOriginal));

        foreach ($config['sizes'] as $size) {
            $variant = clone $image;
            $variant->scale($size['width']);

            $path = sprintf($config['reformat_path'], $size['width'], $size['height']);

            $disk->put(
                $path . '/' . $this->newOriginalFileName,
                $variant->encodeByExtension($config['format'], $config['compression'])
            );
        }
    }
}
