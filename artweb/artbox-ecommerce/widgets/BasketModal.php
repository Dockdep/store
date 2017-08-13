<?php
    namespace artweb\artbox\ecommerce\widgets;
    
    use artweb\artbox\ecommerce\models\ProductVariant;
    use yii\base\Widget;
    
    class BasketModal extends Widget
    {
        
        public function init()
        {
            
            parent::init();
            
        }
        
        public function run()
        {
            $sessionData = \Yii::$app->session->get('order');
            unset( $sessionData[ 'order_id' ] );
            $count = count($sessionData);
            $price = 0;
            if (is_array($sessionData) && !empty( $sessionData )) {
                
                $variant = ProductVariant::find()
                                         ->where([ 'product_variant_id' => array_keys($sessionData) ])
                                         ->indexBy('product_variant_id')
                                         ->all();
                
                foreach ($sessionData as $k => $item) {
                    $sessionData[ $k ][ 'item' ] = $variant[ $k ];
                    $price += $variant[ $k ]->price * $sessionData[ $k ][ 'num' ];
                }
                
                return $this->render(
                    'basket_modal',
                    [
                        'items' => $sessionData,
                        'count' => $count,
                        'price' => $price,
                    ]
                );
                
            }
            
        }
        
    }
    