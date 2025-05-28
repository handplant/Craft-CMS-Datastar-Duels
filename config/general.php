<?php

use craft\config\GeneralConfig;
use craft\helpers\App;

$isDev = App::env('CRAFT_ENVIRONMENT') === 'dev';
$isProd = App::env('CRAFT_ENVIRONMENT') === 'production';

return GeneralConfig::create()
    ->isSystemLive(App::env('IS_SYSTEM_LIVE') ?? false)
    ->enableTemplateCaching(App::env('TEMPLATE_CACHE') ?? true)
    ->devMode(App::env('DEV_MODE') ?? false)
    ->allowAdminChanges(App::env('ALLOW_ADMIN_CHANGES') ?? false)
    ->allowUpdates(App::env('ALLOW_UPDATES') ?? false)
    ->disallowRobots(App::env('DISALLOW_ROBOTS') ?? false)
    ->preloadSingles()
    ->defaultWeekStartDay(1)
    ->omitScriptNameInUrls()
    ->preventUserEnumeration()
    ->maxUploadFileSize('100M')
    ->errorTemplatePrefix("_errors/")
    ->allowedFileExtensions(['jpg', 'png', 'jpeg', 'gif', 'svg', 'mp4', 'wov', 'mp3', 'wav', 'pdf', 'json'])
    ->cacheDuration(0)
    ->generateTransformsBeforePageLoad(true)
    ->verificationCodeDuration(172800) // 2Tage
    ->aliases([
        '@webroot' => dirname(__DIR__) . '/web',
    ])
    ->loginPath('/login')
    ->verifyEmailSuccessPath('/login?emailVerified=1')
    ->activateAccountSuccessPath('/login?accountActivated=1');
