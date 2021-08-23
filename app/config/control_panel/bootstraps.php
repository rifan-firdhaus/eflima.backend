<?php
$bootstraps = [
    'core',
    'control_panel',
];

if (YII_ENV_DEV) {
    $bootstraps[] = 'debug';
    $bootstraps[] = 'gii';
}

return $bootstraps;
