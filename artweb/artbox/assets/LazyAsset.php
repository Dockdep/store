<?php

namespace artweb\artbox\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class LazyAsset extends AssetBundle
{
    public $sourcePath = '@artweb/artbox/resources';
    public $js = [
        'artbox-lazy.js',
    ];

    public $depends = [
        'artweb\artbox\assets\LazyLoadAsset',
    ];

}
