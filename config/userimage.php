<?php

return [
    'disk' => env('FILESYSTEM_DISK', 'local') === 's3' ? 's3' : 'images',
    'sizes' => [
        'xs' => ['width' => 64,  'height' => 64],
        'sm' => ['width' => 128, 'height' => 128],
        'md' => ['width' => 256, 'height' => 256],
        'lg' => ['width' => 512, 'height' => 512],
    ],
    'original_path' => 'users/originals',
    'reformat_path' => 'users/variants/%sx%s',
    'format' => ['webp', 'jpg', 'png', 'jpeg'],
    'compression' => 80,
];
