<?php
namespace backend\assets;

use yii\web\AssetBundle;

class BootstrapNotifyAsset extends AssetBundle
{
    public $sourcePath = '@bower/remarkable-bootstrap-notify/dist';
    public $js = [
        'bootstrap-notify.min.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
