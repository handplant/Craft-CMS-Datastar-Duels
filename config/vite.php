<?php

use craft\helpers\App;

$useDevServer = App::env('ENVIRONMENT') === 'dev' || App::env('CRAFT_ENVIRONMENT') === 'dev';
return [
    'devServerInternal' => 'http://localhost:3000/',
    'devServerPublic' => 'http://localhost:3000/',
    'errorEntry' => 'src/js/app.js',
    'manifestPath' => Craft::getAlias('@webroot') . '/dist/.vite/manifest.json',
    'serverPublic' => Craft::getAlias('@web')  . '/dist/',
    'checkDevServer' => $useDevServer,
    'useDevServer' => $useDevServer,
];
