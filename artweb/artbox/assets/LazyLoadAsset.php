<?php

namespace artweb\artbox\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class LazyLoadAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery.lazyload';
    public $js = [
        'jquery.lazyload.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

}
