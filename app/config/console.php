<?php

use eflima\core\Core;
use yii\log\FileTarget;
use yii\rbac\DbManager;

require_once 'init.php';

return [
    'name' => 'Eflima',
    'id' => 'snc-eflima-v3.2',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@tests' => '@app/tests',
        '@webroot' => dirname(dirname(__DIR__)),
    ],
    'modules' => [
        'core' => Core::class,
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => null,
            'migrationNamespaces' => [
                'eflima\account\migrations',
                'eflima\core\migrations',
                'eflima\control_panel\migrations',
                'eflima\note\migrations',
            ],
        ],
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'authManager' => [
            'class' => DbManager::class,
            'itemTable' => "{{%account_auth_item}}",
            'itemChildTable' => "{{%account_auth_item_child}}",
            'assignmentTable' => "{{%account_auth_assignment}}",
            'ruleTable' => "{{%account_auth_rule}}",
        ],
        'db' => require_once 'database.php',
    ],
];
