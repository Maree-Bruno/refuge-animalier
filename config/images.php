<?php

return [
    'avatar' => [
        'disk' => 'images',
        'sizes' => [
            'sm' => ['width' => 300, 'height' => 300],
            'md' => ['width' => 600, 'height' => 600],
            'lg' => ['width' => 900, 'height' => 900],
        ],
        'original_path' => 'contacts/originals',
        'reformat_path' => 'contacts/variants/%sx%s',
        'compression' => 80,
        'format' => 'jpg',
    ],

    'product_image' => [
        'disk' => 'images',
        'sizes' => [
            'thumb' => ['width' => 150, 'height' => 150],
            'medium' => ['width' => 500, 'height' => 500],
            'large' => ['width' => 1200, 'height' => 1200],
        ],
        'original_path' => 'products/originals',
        'reformat_path' => 'products/variants/%sx%s',
        'compression' => 85,
        'format' => 'jpg',
    ],
];
