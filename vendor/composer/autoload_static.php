<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdd313ebb750adbe871331385307f7c60
{
    public static $files = array (
        '9e4824c5afbdc1482b6025ce3d4dfde8' => __DIR__ . '/..' . '/league/csv/src/functions_include.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Component\\Process\\' => 26,
            'Spatie\\DbDumper\\' => 16,
            'SherifAI\\ClearCut\\' => 18,
        ),
        'L' => 
        array (
            'League\\Csv\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Component\\Process\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/process',
        ),
        'Spatie\\DbDumper\\' => 
        array (
            0 => __DIR__ . '/..' . '/spatie/db-dumper/src',
        ),
        'SherifAI\\ClearCut\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'League\\Csv\\' => 
        array (
            0 => __DIR__ . '/..' . '/league/csv/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'W' => 
        array (
            'Webpatser\\Uuid' => 
            array (
                0 => __DIR__ . '/..' . '/webpatser/laravel-uuid/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitdd313ebb750adbe871331385307f7c60::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitdd313ebb750adbe871331385307f7c60::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitdd313ebb750adbe871331385307f7c60::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}