<?php
    
    namespace artweb\artbox\ecommerce\widgets;
    
    use artweb\artbox\ecommerce\helpers\ProductHelper;
    use yii\base\Widget;
    
    class lastProducts extends Widget
    {
        
        public function init()
        {
            parent::init();
        }
        
        public function run()
        {
            return $this->render(
                'products_block',
                [
                    'title'    => \Yii::t('product', 'Вы недавно просматривали'),
                    'class'    => 'last-products',
                    'products' => ProductHelper::getLastProducts(true),
                ]
            );
        }
    }
    