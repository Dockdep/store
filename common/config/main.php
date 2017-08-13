<?php
    return [
        'language'   => 'ru',
        'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
        'controllerMap'       => [
            'siteMap' => [
                'class'    => 'artweb\artbox\ecommerce\console\SiteMapController',
            ],
        ],
        'components' => [
            'elasticsearch' => [
                'class' => 'yii\elasticsearch\Connection',
                'nodes' => [
                    ['http_address' => '127.0.0.1:9200'],
                    // configure more hosts if you have a cluster
                ],
            ],
            'mailer' => [
                'class' => 'yii\swiftmailer\Mailer',
                'transport' => [
                    'class' => 'Swift_SmtpTransport',
                    'host' => 'smtp.gmail.com',
                    'username' => 'store.extremstyle@gmail.com',
                    'password' => 'Sputnik123',
                    'port' => '587',
                    'encryption' => 'tls',
                ],
            ],
            'cache' => [
                'class' => 'yii\caching\MemCache',
                'keyPrefix' => 'extrem_'
            ],
            'sender' => [
                'class' => 'artweb\artbox\components\SmsSender',
            ],
            'artboximage' => [
                'class'    => 'artweb\artbox\components\artboximage\ArtboxImage',
                'driver'   => 'GD',
                'rootPath' => Yii::getAlias('@storage'),
                'rootUrl'  => Yii::getAlias('/storage'),
                'presets'  => include_once "presets.php",
            ],
            'basket'      => [
                'class' => 'artweb\artbox\ecommerce\models\Basket',
            ],
            'formatter' => [
                'timeZone' => 'Europe/Kiev',
            ],
        ],

    ];
