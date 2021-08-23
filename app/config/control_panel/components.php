<?php

use eflima\control_panel\models\AdministratorAccount;
use eflima\core\components\Setting;
use eflima\core\rest\Response;
use yii\caching\FileCache;
use yii\log\FileTarget;
use yii\web\Cookie;
use yii\web\JsonParser;
use yii\web\JsonResponseFormatter;
use yii\web\Session;
use yii\web\UrlManager;
use yii\web\User;

return [
    'db' => require(__DIR__ . '/../database.php'),
    'request' => [
        'cookieValidationKey' => 'whz4hnrZysItlu3bfP3072ztfb7V6W9tjtJLV294aHj8xP8reZwy8ffdZsRMhVi3',
        'enableCsrfValidation' => true,
        'enableCookieValidation' => true,
        'enableCsrfCookie' => false,
        'parsers' => [
            'application/json' => JsonParser::class,
        ],
    ],
    'assetManager' => [
        'basePath' => '@webroot/assets/control_panel',
        'baseUrl' => '@web/assets/control_panel',
    ],
    'response' => [
        'class' => Response::class,
        'formatters' => [
            Response::FORMAT_JSON => [
                'class' => JsonResponseFormatter::class,
                'prettyPrint' => YII_DEBUG,
            ],
        ],
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
    'user' => [
        'class' => User::class,
        'identityClass' => AdministratorAccount::class,
        'enableSession' => true,
        'identityCookie' => [
            'name' => '_identity',
            'httpOnly' => true,
            'sameSite' => Cookie::SAME_SITE_LAX,
        ],
    ],
    'session' => [
        'class' => Session::class,
        'name' => 'forher-control-panel',
    ],
    'setting' => [
        'class' => Setting::class,
    ],
];
