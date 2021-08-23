<?php
// comment out the following two lines when deployed to production

use eflima\control_panel\web\Application;

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/app/vendor/autoload.php';
require __DIR__ . '/app/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/app/config/control_panel/app.php';

(new Application($config))->run();
