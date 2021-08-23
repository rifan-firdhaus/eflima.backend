<?php
require_once __DIR__ . '/init.php';

return [
    'name' => 'Eflima ControlPanel',
    'id' => 'eflima.control-panel.v0.0.1',
    'sourceLanguage' => 'en-US',
    'vendorPath' => '@vendor',
    'basePath' => '@app',
    'runtimePath' => '@runtime',
    'components' => require_once __DIR__ . '/components.php',
    'modules' => require_once __DIR__ . '/modules.php',
    'bootstrap' => require_once __DIR__ . '/bootstraps.php',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
];
