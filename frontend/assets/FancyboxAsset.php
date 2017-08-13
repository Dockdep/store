<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Fancybox asset bundle.
 */
class FancyboxAsset extends AssetBundle
{
    public $sourcePath = '@bower/fancybox/source';
    public $js = [
        'jquery.fancybox.pack.js',
    ];
    public $css = [
        'jquery.fancybox.css',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
