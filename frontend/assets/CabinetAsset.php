<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main frontend application asset bundle.
 */
class CabinetAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/css/cabinet.css'
    ];
    public $js = [
        '/js/ab_cab.js',
        '/js/iosCheckbox.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public $jsOptions = [
        'position' =>  View::POS_END
    ];

}
