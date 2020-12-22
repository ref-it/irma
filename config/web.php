<?php

use app\components\Config;
use yii\i18n\PhpMessageSource;
use yii\log\FileTarget;
use yii\swiftmailer\Mailer;
use yii\caching\FileCache;

if(file_exists(__DIR__ . '/secrets.php')){
    $secrets = require  __DIR__ . '/secrets.php';
}else{
    $secrets = require  __DIR__ . '/secrets.sample.php';
    touch(__DIR__ . '/INSTALLING_NOW');
}

if(file_exists(__DIR__ . '/auth.php')) {
    $authFile = require __DIR__ . '/auth.php';
}else {
    $authFile = require  __DIR__ . '/auth.sample.php';
}

if(file_exists(__DIR__ . '/INSTALLING_NOW')){
    define('START_INSTALLER', true);
}

$config = [
    'id' => 'yii-app',
    'name' => 'Yii App',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'de-DE', // default changed by Config

    'aliases' => [
        '@bower' => '@vendor/bower',
        '@npm'   => '@vendor/npm',
        '@locale'   => '@app/locale',
    ],
    'components' => [

        'request' => [
            'cookieValidationKey' => $secrets['cookieValidationKey'] ?? '',
        ],

        'cache' => [
            'class' => FileCache::class,
        ],

        'user' => [
            'class' => yii\web\User::class,
            'identityClass' => \app\models\MixedUserIdentity::class,
        ],

        'errorHandler' => [
            'errorAction' => 'site/error'
        ],
        
        'mailer' => [
            'class' => Mailer::class,
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],

        'db' => $secrets['db'] ?? [],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                //enter routing rules here
                'migrate/<action:\w*>' => 'migrate/catch-all',
            ],
        ],

        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => yii\i18n\PhpMessageSource::class
                ],
            ],
        ],

        'config' => [
            'class' => Config::class,
        ],

        'view' => [
            'class' => \daxslab\taggedview\View::class,
            // @see https://github.com/daxslab/yii2-taggedview
            //configure some default values that will be shared by all the pages of the website
            //if they are not overwritten by the page itself
            //'image' => 'http://domain.com/images/default-image.jpg',
        ],

    ],
    'params' => [],
];

if($authFile['enable-ldap'] !== false){
    $config['components']['ldapAuth'] = $authFile['ldap'];
    $config['components']['ldapAuth']['class'] = \stmswitcher\Yii2LdapAuth\LdapAuth::class;
}

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => \yii\debug\Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => \yii\gii\Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
