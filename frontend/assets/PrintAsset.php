<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class PrintAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/css/print.css',
//        '//fonts.googleapis.com/css?family=Ubuntu:400,700,400italic&subset=latin,cyrillic,cyrillic-ext'
    ];


}
