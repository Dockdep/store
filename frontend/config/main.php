<?php
    
    use frontend\models\Redirector;
    
    $params = array_merge(
        require( __DIR__ . '/../../common/config/params.php' ),
        require( __DIR__ . '/../../common/config/params-local.php' ),
        require( __DIR__ . '/params.php' ),
        require( __DIR__ . '/params-local.php' )
    );
    
    return [
        'homeUrl'             => '/',
        'id'                  => 'app-frontend',
        'name'                => 'Extrem Style',
        'basePath'            => dirname(__DIR__),
        'bootstrap'           => [ 'log' ],
        'controllerNamespace' => 'frontend\controllers',
        'modules'             => [
            'artbox-comment' => [
                'class' => 'artweb\artbox\comment\Module',
            ],
        ],
        'on beforeRequest'    => function ($event) {
            Redirector::processOld();
        },
        'components'          => [
            
            'assetManager' => [
                'bundles' => array_merge(
                                    require( __DIR__ . '/' . 'assets-prod.php' ),
                    [],
                    [
                        'artweb\artbox\comment\assets\CommentAsset' => [
                            'css' => [
                                'rateit.css',
                            ],
                        ],
                    ]
                ),
            ],
            'request'      => [
                'baseUrl'             => '/',
                'cookieValidationKey' => 'ndahjhjjidasuidrqeswuiuirqw89',
                'csrfParam'           => '_frontendCSRF',
                'class'               => 'artweb\artbox\language\components\LanguageRequest',
            ],
            'user'         => [
                'identityClass'   => 'artweb\artbox\models\Customer',
                'enableAutoLogin' => true,
                'identityCookie'  => [
                    'name'     => '_identity-frontend',
                    'httpOnly' => true,
                ],
            ],
            'session'      => [
                // this is the name of the session cookie used for login on the frontend
                'name' => 'advanced-frontend',
            ],
            'log'          => [
                'traceLevel' => YII_DEBUG ? 3 : 0,
                'targets'    => [
                    [
                        'class'  => 'yii\log\FileTarget',
                        'levels' => [
                            'error',
                            'warning',
                        ],
                    ],
                ],
            ],
            'errorHandler' => [
                'errorAction' => 'error/error',
            ],
            'urlManager'   => [
                'baseUrl'         => '/',
                'enablePrettyUrl' => true,
                'showScriptName'  => false,
                'class'           => 'artweb\artbox\language\components\LanguageUrlManager',
                'rules'           => [
                    'product/<product:[\w-]+>/<variant:[\w-]+>'                        => 'catalog/product',
                    [
                        'class'     => 'artweb\artbox\ecommerce\CatalogUrlManager',
                        'route_map' => [
                            'catalog' => 'catalog/category',
                            
                            //                            '<product:^(.+)\.htm$>' => 'catalog/product',
                        ],
                    ],
                    '/'                                                                => 'site/index',
                    'site/page/<slug:[\w-]+>'                                          => 'site/page',
                    'blog/view/<slug:[\w-]+>'                                          => 'blog/view',
                    'blog/<slug:[\w-]+>'                                               => 'blog/category',
                    '<controller:(blog|brand)>/<action:(category|view)>/<slug:[\w-]+>' => '<controller>/<action>',
                    'site/confirm/<id:[\w-]+>/<key:[\w-]+>'                            => 'site/confirm',
                    'event/<type:\w+>/show/<alias:[\w_-]+>'                            => 'event/show',
                    'site/reset-password/<token:[\w-]+>'                               => 'site/reset-password',
                    '<controller:[\w-]+>/<action:[\w-]+>'                              => '<controller>/<action>',
                ],
            ],
            'i18n'         => [
                'translations' => [
                    'app*'           => [
                        'class'          => 'yii\i18n\PhpMessageSource',
                        'basePath'       => '@frontend/translations',
                        'sourceLanguage' => 'en-EN',
                        'fileMap'        => [
                            'app' => 'app.php',
                        ],
                    ],
                    'product'        => [
                        'class'    => 'yii\i18n\PhpMessageSource',
                        'basePath' => '@artweb/artbox/translation',
                        'fileMap'  => [
                            'product' => 'product.php',
                        ],
                    ],
                    'artbox-comment' => [
                        'class'    => 'yii\i18n\PhpMessageSource',
                        'basePath' => '@artweb/artbox/comment/messages',
                    ],
                ],
            ],
            'language'     => 'ru-RU',
        ],
        'params'              => $params,
    ];
