<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Laravel\Facades\Image;

class ProcessUploadedImage implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $fullPathToOriginal,
        public string $filename,
        public string $configKey
    ) {}

    public function handle(): void
    {
        Log::info('=== DEBUT ProcessUploadedImage ===');
        Log::info('fullPathToOriginal: ' . $this->fullPathToOriginal);
        Log::info('filename: ' . $this->filename);

        try {
            $config = config($this->configKey);
            Log::info('Config:', $config);

            $disk = $config['disk'];

            // Vérifier si le fichier existe
            $exists = Storage::disk($disk)->exists($this->fullPathToOriginal);
            Log::info('Fichier existe sur disk "' . $disk . '": ' . ($exists ? 'OUI' : 'NON'));

            if (!$exists) {
                Log::error('FICHIER INTROUVABLE: ' . $this->fullPathToOriginal);
                Log::info('Contenu du dossier animals/originals:');
                $files = Storage::disk($disk)->files('animals/originals');
                Log::info('Files:', $files);
                return;
            }

            $imageData = Storage::disk($disk)->get($this->fullPathToOriginal);
            Log::info('Données image chargées: ' . strlen($imageData) . ' bytes');

            $image = Image::read($imageData);

            foreach ($config['sizes'] as $sizeName => $size) {
                Log::info("Création variant {$sizeName}: {$size['width']}x{$size['height']}");

                $variant = clone $image;
                $variant->scale($size['width'], $size['height']);

                $path = sprintf(
                    $config['reformat_path'],
                    $size['width'],
                    $size['height']
                );

                $fullPath = $path . '/' . $this->filename;
                Log::info('Chemin complet du variant: ' . $fullPath);

                $imageType = $config['format'][0];
                $encoded = $variant->encodeByExtension($imageType, $config['compression']);

                Storage::disk($disk)->put($fullPath, $encoded);

                // Vérifier que le fichier a bien été créé
                $created = Storage::disk($disk)->exists($fullPath);
                Log::info('Fichier créé: ' . ($created ? 'OUI' : 'NON'));

                if ($created) {
                    $fullSystemPath = Storage::disk($disk)->path($fullPath);
                    Log::info('Chemin système complet: ' . $fullSystemPath);
                }
            }

            Log::info('=== FIN ProcessUploadedImage SUCCESS ===');

        } catch (\Exception $e) {
            Log::error('ERREUR: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            throw $e;
        }
    }
}
