<?php

declare( strict_types=1 );

use Isolated\Symfony\Component\Finder\Finder;

return [
    'prefix' => 'Shop_Maestro_Vendor', // Change to your desired namespace prefix
    'finders' => [
        Finder::create()
            ->files()
            ->in('vendor') // Scope everything in vendor
            ->name('*.php')
    ]
];