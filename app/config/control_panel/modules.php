<?php

use eflima\account\components\oauth2\OAuth2;
use eflima\account\components\oauth2\storages\ClientCredentials;
use eflima\control_panel\components\oauth2\storages\AccessToken;
use eflima\control_panel\components\oauth2\storages\AuthorizationCode;
use eflima\control_panel\components\oauth2\storages\RefreshToken;
use eflima\control_panel\components\oauth2\storages\UserCredentials;
use eflima\control_panel\ControlPanel;
use eflima\core\Core;
use yii\debug\Module as DebugModule;
use yii\gii\Module as GiiModule;

$modules = [
    'core' => [
        'class' => Core::class,
    ],
    'control_panel' => [
        'class' => ControlPanel::class,
        'components' => [
            'oauth2' => [
                'class' => OAuth2::class,
                'storages' => [
                    'access_token' => AccessToken::class,
                    'authorization_code' => AuthorizationCode::class,
                    'client_credentials' => ClientCredentials::class,
                    'user_credentials' => UserCredentials::class,
                    'refresh_token' => RefreshToken::class,
                ],
            ],
        ],
    ],
];

if (YII_ENV_DEV) {
    $modules['debug'] = [
        'class' => DebugModule::class,
        'traceLine' => '<a href="phpstorm://open?url={file}&line={line}">{file}:{line}</a>',
    ];
    $modules['gii'] = [
        'class' => GiiModule::class,
        'generators' => [
            'model' => [
                'class' => 'yii\gii\generators\model\Generator',
                'templates' => [
                    'Eflima Model' => '@eflima/core/gii-templates',
                ],
            ],
        ],
    ];
}

return $modules;
