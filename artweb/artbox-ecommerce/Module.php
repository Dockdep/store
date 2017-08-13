<?php
    
    namespace artweb\artbox\ecommerce;
    
    /**
     * product module definition class
     */
    class Module extends \yii\base\Module
    {
        
        public $types = [];
    
        /**
         * @inheritdoc
         */
        public function init()
        {
            parent::init();
            
            \Yii::configure($this, require( __DIR__ . '/config.php' ));
        }
    }
