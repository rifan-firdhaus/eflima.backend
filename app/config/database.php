<?php

use yii\db\Connection;

return [
    'class' => Connection::class,
    'enableQueryCache' => false,
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 3600 * 24 * 30,
    'dsn' => 'mysql:host=localhost;dbname=snc_eflima_v3.2',
    'username' => 'root',
    'password' => 'rifan123',
    'charset' => 'utf8mb4',
];
