<?php
    /**
     * @link      http://www.yiiframework.com/
     * @copyright Copyright (c) 2008 Yii Software LLC
     * @license   http://www.yiiframework.com/license/
     */
    
    namespace artweb\artbox\file;
    
    use yii\web\AssetBundle;
    
    /**
     * Asset bundle for the Twitter bootstrap javascript files.
     *
     * @author Qiang Xue <qiang.xue@gmail.com>
     * @since  2.0
     */
    class FileUploadAsset extends AssetBundle
    {
        
        /**
         * @inheritdoc
         */
        public function init()
        {
            parent::init();
            $this->sourcePath = __DIR__ . '/assets';
        }
        
        public $css = [
            'css/jquery.fileupload.css',
            'css/fileupload/style.css',
        ];
        
        public $js = [
            'js/vendor/jquery.ui.widget.js',
            'js/jquery.iframe-transport.js',
            'js/jquery.fileupload.js',
        ];
    }
