<?php
    
    namespace artweb\artbox;
    
    class Module extends \yii\base\Module
    {
        public function init()
        {
            parent::init();
            
            $this->modules = [
                'catalog' => [
                    'class' => '\artweb\artbox\modules\catalog\Module',
                ],
            ];
        }
    }
    