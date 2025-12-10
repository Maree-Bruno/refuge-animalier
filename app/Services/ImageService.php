<?php
namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcessUploadedImage;

class ImageService
{
    public function __construct(
        protected string $configKey
    ) {}

    public function store(UploadedFile $file): string
    {
        $config = config($this->configKey);

        $newFileName = uniqid('', true) . '.' . $config['format'];
        $originalPath = $config['original_path'];

        $fullPath = Storage::disk($config['disk'])->putFileAs(
            $originalPath,
            $file,
            $newFileName
        );

        if ($fullPath) {
            ProcessUploadedImage::dispatch($fullPath, $newFileName, $this->configKey);
            return $newFileName;
        }

        throw new \Exception('Échec de la sauvegarde de l\'image');
    }

    /**
     * Supprime l'image et toutes ses variantes
     */
    public function delete(string $fileName): void
    {
        $config = config($this->configKey);
        $disk = Storage::disk($config['disk']);

        // Supprime l'original
        $disk->delete($config['original_path'] . '/' . $fileName);

        // Supprime les variantes
        foreach ($config['sizes'] as $size) {
            $path = sprintf($config['reformat_path'], $size['width'], $size['height']);
            $disk->delete($path . '/' . $fileName);
        }
    }

    /**
     * Retourne l'URL d'une variante spécifique
     */
    public function getUrl(string $fileName, string $size = 'md'): string
    {
        $config = config($this->configKey);
        $sizeConfig = $config['sizes'][$size];

        $path = sprintf(
            $config['reformat_path'],
            $sizeConfig['width'],
            $sizeConfig['height']
        );

        return asset($path . '/' . $fileName);
    }
}
