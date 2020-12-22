<?php

use webvimark\modules\UserManagement\UserManagementModule;
use yii\log\FileTarget;
use yii\caching\FileCache;
use yii\gii\Module;

if(file_exists(__DIR__ . '/secrets.php')){
    $secrets = require  __DIR__ . '/secrets.php';
}else{
    $secrets = require  __DIR__ . '/secrets.sample.php';
    define('START_INSTALLER', true);
}

$config = [
    'id' => 'yii-app-console',
    'name' => 'Yii App',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower',
        '@npm'   => '@vendor/npm',
        '@tests' => '@app/tests',
    ],
    'components' => [
        'cache' => [
            'class' => FileCache::class,
        ],
        'log' => [
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $secrets['db'] ?? [],
    ],
    'params' => [],
    'controllerMap' => [
        /* @see https://www.yiiframework.com/doc/guide/2.0/en/db-migrations#namespaced-migrations */
        'migrate' => [
            'class' => yii\console\controllers\MigrateController::class,
            'migrationPath' => [
                '@app/migrations', // disable non-namespaced migrations if app\migrations is listed below
            ],
        ],
    ],
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => Module::class,
    ];
}

return $config;
