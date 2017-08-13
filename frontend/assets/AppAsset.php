<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/css/style.min.css',
        '/css/rateit.css',
    ];
    public $js = [
        '/js/script.js',
        '/js/artbox_basket.js',
        '/js/jquery.mask.min.js',
        '/js/jquery.jcarousel.min.js',
        '/js/owl.carousel.min.js',
        '/js/flipclock.min.js',
        '/js/jquery.rateit.min.js',
        '/js/device.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
        'frontend\assets\CdnAsset',
        'yii\\widgets\\MaskedInputAsset',
        'yii\\widgets\\ActiveFormAsset',
        'yii\\validators\\ValidationAsset',
        'artweb\artbox\assets\LazyAsset',
        'frontend\assets\FancyboxAsset',
    ];

    public $jsOptions = [
        'position' =>  View::POS_END
    ];

}
