<?php
    
    namespace artweb\artbox\assets;
    
    use yii\web\AssetBundle;
    
    class AdminLteAsset extends AssetBundle
    {
        public $sourcePath = '@bower/';
        public $css = [
            'admin-lte/dist/css/AdminLTE.css',
            'admin-lte/dist/css/skins/_all-skins.css',
        ];
        
        public $js = [
            'admin-lte/dist/js/app.js',
        
        ];
        public $depends = [
            'yii\web\YiiAsset',
            'yii\bootstrap\BootstrapPluginAsset',
            'artweb\artbox\assets\FontAwesomeAsset',
        ];
    }