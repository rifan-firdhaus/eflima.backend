<?php

use yii\caching\FileCache;
use yii\log\FileTarget;
use yii\web\UrlManager;

return [
    'db' => require(__DIR__ . '/database.php'),
    'request' => [
        'cookieValidationKey' => 'gSKY@nT9xXo1f4g31>G&Y"49z34S1p_new',
        'enableCsrfValidation' => true,
        'enableCookieValidation' => true,
        'enableCsrfCookie' => false,
    ],
    'cache' => [
        'class' => FileCache::class,
        'directoryLevel' => 3,
    ],
    'urlManager' => [
        'class' => UrlManager::class,
        'enablePrettyUrl' => true,
        'showScriptName' => true,
    ],
    'log' => [
        'traceLevel' => YII_DEBUG ? 1 : 0,
        'targets' => [
            [
                'class' => FileTarget::class,
                'levels' => ['error', 'warning'],
            ],
        ],
    ],
];
