<?php

return [
    'disable' => env('CAPTCHA_DISABLE', false),

    'characters' => [
        'A','B','C','D','E','F','G','H','J','K','M','N','P','Q','R','S','T','U','V','W','X','Y','Z',
        'a','b','c','d','e','f','g','h','j','k','m','n','p','q','r','s','t','u','v','w','x','y','z',
        2,3,4,5,6,7,8,9
    ],

    'fontsDirectory' => base_path('vendor/mews/captcha/assets/fonts'),
    'bgsDirectory'   => base_path('vendor/mews/captcha/assets/backgrounds'),

    'default' => [
        'length'  => 5,
        'width'   => 300,
        'height'  => 70,
        'quality' => 100,

        'bgColor'    => '#fdfdfd',
        'fontColors' => ['#1c1c1c', '#2d3436'],

        'angle' => 2,
        'lines' => 0,
        'bgImage' => false,
    ],

    'modern' => [
        'length'  => 5,
        'width'   => 280,
        'height'  => 70,
        'quality' => 100,

        'bgColor' => '#f5f7fa',

        'fontColors' => ['#1e293b'], // Slate-900

        'lines' => 0,
        'bgImage' => false,

        'angle' => 4,

        'contrast' => 0,
    ],

    'mini' => [
        'length' => 4,
        'width'  => 110,
        'height' => 40,

        'bgColor'    => '#ffffff',
        'fontColors' => ['#1e293b'],
        'lines'      => 0,
        'angle'      => 1,
    ],


    'flat' => [
        'length' => 6,
        'width'  => 260,
        'height' => 65,

        'bgColor'    => '#f8fafc',
        'fontColors' => ['#0f172a'], // Dark navy
        'lines'      => 0,
    ],


    'math' => [
        'length'  => 7,
        'width'   => 160,
        'height'  => 50,
        'quality' => 100,

        'bgColor'    => '#ffffff',
        'fontColors' => ['#111827'],
        'lines'      => 0,
        'math'       => true,
    ],
];