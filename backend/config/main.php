<?php
    $params = array_merge(
        require( __DIR__ . '/../../common/config/params.php' ),
        require( __DIR__ . '/../../common/config/params-local.php' ),
        require( __DIR__ . '/params.php' ),
        require( __DIR__ . '/params-local.php' )
    );
    
    return [
        'id'                  => 'app-backend',
        'basePath'            => dirname(__DIR__),
        'controllerMap'       => [
            'elfinder'        => [
                'class'            => 'mihaildev\elfinder\Controller',
                'access'           => [ '@' ],
                //глобальный доступ к фаил менеджеру @ - для авторизорованных , ? - для гостей , чтоб открыть всем ['@', '?']
                'disabledCommands' => [ 'netmount' ],
                //отключение ненужных команд https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#commands
                'roots'            => [
                    [
                        'class' => 'mihaildev\elfinder\volume\UserPath',
                        'path'  => '../../storage/user_{id}',
                        'name'  => 'My Documents',
                    ],
                ],
                'watermark'        => [
                    'source'         => __DIR__ . '/logo.png',
                    // Path to Water mark image
                    'marginRight'    => 5,
                    // Margin right pixel
                    'marginBottom'   => 5,
                    // Margin bottom pixel
                    'quality'        => 95,
                    // JPEG image save quality
                    'transparency'   => 70,
                    // Water mark image transparency ( other than PNG )
                    'targetType'     => IMG_GIF | IMG_JPG | IMG_PNG | IMG_WBMP,
                    // Target image formats ( bit-field )
                    'targetMinPixel' => 200
                    // Target image minimum pixel size
                ],
            ],
        ],
        'layout'              => 'admin',
        'controllerNamespace' => 'backend\controllers',
        'bootstrap'           => [ 'log' ],
        'as AccessBehavior'   => [
            'class' => 'artweb\artbox\behaviors\ArtBoxAccessBehavior',
            'rules' => [
                'permit/access' => [
                    [
                        'actions' => [
                            'role',
                            'permission',
                            'add-role',
                            'update-role',
                            'add-permission',
                            'update-permission',
                        ],
                        'allow'   => true,
                    ],
                ],
                'site'          => [
                    [
                        'actions' => [
                            'login',
                            'error',
                            'logout',
                        ],
                        'allow'   => true,
                    ],
                    [
                        'actions' => [ 'index' ],
                        'allow'   => true,
                        'roles'   => [ '@' ],
                    ],
                    [
                        'actions' => [ 'images' ],
                        'allow'   => true,
                        'roles'   => [ '@' ],
                    ],
                
                ],
                'file/uploader' => [
                    [
                        'actions' => [ 'images-upload' ],
                        'allow'   => true,
                    ],
                ],
                'elfinder'      => [
                    [
                        'actions' => [
                            'manager',
                            'connect',
                        ],
                        'allow'   => true,
                    ],
                ],
            
            ],
        ],
        'modules'             => [
            'permit'         => [
                'class'  => 'developeruz\db_rbac\Yii2DbRbac',
                'params' => [
                    'userClass' => 'backend\models\User',
                ],
            ],
            'ecommerce'      => [
                'class' => 'artweb\artbox\ecommerce\Module',
            ],
            'event'          => [
                'class' => 'artweb\artbox\event\Module',
            ],
            'design'         => [
                'class' => 'artweb\artbox\design\Module',
            ],
            'seo'            => [
                'class' => 'artweb\artbox\seo\Module',
            ],
            'gridview'       => [
                'class' => '\kartik\grid\Module',
            ],
            'file'           => [
                'class' => 'artweb\artbox\file\Module',
            ],
        ],
        'components'          => [
            'authManager'        => [
                'class' => 'yii\rbac\DbManager',
            ],
            'user'               => [
                'identityClass'   => 'common\models\User',
                'enableAutoLogin' => true,
            ],
            'log'                => [
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
            'imageCache'         => [
                'class'      => 'iutbay\yii2imagecache\ImageCache',
                'sourcePath' => '@storage',
                'sourceUrl'  => '/storage',
                'thumbsPath' => '@storage/thumbs',
                'thumbsUrl'  => '/storage/thumbs',
                'sizes'      => [
                    'slider' => [
                        720,
                        340,
                    ],
                ],
            ],
            'errorHandler'       => [
                'errorAction' => 'site/error',
            ],
            'request'            => [
                'baseUrl'             => '/admin',
                'cookieValidationKey' => 'j4iuot9u5894e7tu8reyh78g9y54sy7i',
                'csrfParam'           => '_backendCSRF',
            ],
            'urlManager'         => [
                'baseUrl'         => '/admin',
                'enablePrettyUrl' => true,
                'showScriptName'  => false,
                'rules'           => [
                    'slider-image/<action>/<slider_id:[A-Za-z0-9_-]+>/<id:[A-Za-z0-9_-]+>'          => 'slider-image/<action>',
                    'slider-image/<action>/<slider_id:[A-Za-z0-9_-]+>'                              => 'slider-image/<action>',
                    'rubrication/tax-group/<level:[0-9]+>'                                          => 'rubrication/tax-group',
                    'rubrication/tax-group/<action>/<level:[0-9]+>/<id:[A-Za-z0-9_-]+>'             => 'rubrication/tax-group/<action>',
                    'rubrication/tax-group/<action>/<level:[0-9]+>'                                 => 'rubrication/tax-group/<action>',
                    'product/manage/<action>'                                                       => 'product/manage/<action>',
                    'product/<controller>/<action>/<product_id:[A-Za-z0-9_-]+>/<id:[A-Za-z0-9_-]+>' => 'product/<controller>/<action>',
                    'product/<controller>/<action>/<product_id:[A-Za-z0-9_-]+>/'                    => 'product/<controller>/<action>',
                    'seo-dynamic/<action>/<seo_category_id:[A-Za-z0-9_-]+>/<id:[A-Za-z0-9_-]+>'     => 'seo-dynamic/<action>',
                    'seo-dynamic/<action>/<seo_category_id:[A-Za-z0-9_-]+>'                         => 'seo-dynamic/<action>',
                ],
            ],
            'urlManagerFrontend' => [
                'baseUrl'         => '/',
                'enablePrettyUrl' => true,
                'showScriptName'  => false,
                'class'           => 'artweb\artbox\language\components\LanguageUrlManager',
                'rules'           => [
                    '/'                             => 'site/index',
                    '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                ],
            ],
            'i18n'               => [
                'translations' => [
                    'artbox-comment' => [
                        'class'    => 'yii\i18n\PhpMessageSource',
                        'basePath' => '@artweb/artbox/comment/messages',
                    ],
                    '*'              => [
                        'class'    => 'yii\i18n\PhpMessageSource',
                        'basePath' => '@artweb/artbox/translation',
                        'fileMap'  => [
                            'app'       => 'app.php',
                            'app/error' => 'error.php',
                        ],
                    ],
                    'app'            => [
                        'class'    => 'yii\i18n\PhpMessageSource',
                        'basePath' => '@artweb/artbox/translation',
                        'fileMap'  => [
                            'app'       => 'app.php',
                            'app/error' => 'error.php',
                        ],
                    ],
                ],
            ],
        
        ],
        'params'              => $params,
    ];
