<?php

return [
    'disk' => env('FILESYSTEM_DISK') === 's3' ? 's3' : 'images',
    'sizes' => [
        'sm' => ['width' => 300, 'height' => 300],
        'md' => ['width' => 600, 'height' => 600],
        'lg' => ['width' => 900, 'height' => 900],
    ],
    'original_path' => 'animals/originals',
    'reformat_path' => 'animals/variants/%sx%s',
    'format' => ['webp', 'jpg', 'png', 'jpeg'],
    'compression' => 80,
];
