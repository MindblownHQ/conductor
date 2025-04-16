<?php

declare( strict_types=1 );

use Isolated\Symfony\Component\Finder\Finder;

return [
    'prefix' => 'Shop_Maestro_Vendor', // Change to your desired namespace prefix
	'exclude-files' => [
        // Prevent scoping Composer's internal autoloader
        __DIR__ . '/vendor/composer/autoload_real.php',
        __DIR__ . '/vendor/composer/autoload_static.php',
    ],

    'patchers' => [
        // Prevent scoping internal Composer classes
        function (string $filePath, string $prefix, string $content): string {
            if (strpos($filePath, '/vendor/composer/') !== false) {
                return str_replace("{$prefix}\\Composer\\", 'Composer\\', $content);
            }
            return $content;
        },
    ],
    'finders' => [
        Finder::create()
            ->files()
            ->in('vendor') // Scope everything in vendor
            ->name('*.php')
    ]
];