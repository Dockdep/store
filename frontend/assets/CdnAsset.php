<?php
    
    namespace frontend\assets;
    
    use yii\web\AssetBundle;
    use yii\web\View;
    
    class CdnAsset extends AssetBundle
    {
        public $css = [
            'https://fonts.googleapis.com/css?family=Roboto:400,500,700',
        ];
        public $js = [
            'https://code.jquery.com/jquery-migrate-1.3.0.min.js',
            'https://code.jquery.com/ui/1.11.4/jquery-ui.min.js',
            '//www.googleadservices.com/pagead/conversion.js',
//            'https://vk.com/js/api/openapi.js?121',
        ];
        
        public $jsOptions = [
            'position' => View::POS_END,
        ];
        
    }
