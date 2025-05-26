<?php

use craft\helpers\App;

return [
    'siteName' => [
        'default' => App::env('SITE_NAME'),
    ],
    'defaultProfile' => 'default',
    'fieldProfiles' => [
        'default' => [
            'title' => ['title']
        ],
    ],
    'sitemapEnabled' => true,
    'outputAlternate' => false,
    'sitemapConfig' => [
        'elements' => [
            'home' => ['changefreq' => 'weekly', 'priority' => 1],
            'duels' => [
                'elementType' => \craft\elements\Entry::class,
                'params' => ['changefreq' => 'weekly', 'priority' => 1],
            ],
            'rivals' => [
                'elementType' => \craft\elements\Entry::class,
                'params' => ['changefreq' => 'weekly', 'priority' => 1],
            ],
        ],
    ],
];
