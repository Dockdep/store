<?php
    
    namespace artweb\artbox\modules\comment\assets;
    
    use yii\web\AssetBundle;
    
    /**
     * Class CommentAsset
     * @package artweb\artbox\modules\comment\assets
     */
    class CommentAsset extends AssetBundle
    {
        
        /**
         * @inheritdoc
         */
        public $sourcePath = '@artbox-comment/resources';
        
        /**
         * @inheritdoc
         */
        public $js = [
            'artbox_comment.js',
            'jquery.rateit.min.js',
        ];
        
        /**
         * @inheritdoc
         */
        public $css = [
            'artbox_comment.css',
            'rateit.css',
        ];
        
        /**
         * @inheritdoc
         */
        public $depends = [
            'yii\web\JqueryAsset',
            'yii\web\YiiAsset',
        ];
    }