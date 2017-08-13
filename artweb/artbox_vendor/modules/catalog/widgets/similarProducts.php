<?php
    
    namespace artweb\artbox\modules\catalog\widgets;
    
    use artweb\artbox\modules\catalog\helpers\ProductHelper;
    use yii\base\Widget;
    use Yii;
    
    class similarProducts extends Widget
    {
        
        public $count = 10;
        
        public $title;
        
        public $product;
        
        public function init()
        {
            parent::init();
        }
        
        public function run()
        {
            $products = ProductHelper::getSimilarProducts($this->product, $this->count);
            
            if (!$this->title) {
                $this->title = Yii::t('product', 'Similar products');
            }
            
            return $this->render(
                'products_block',
                [
                    'title'    => $this->title,
                    'class'    => 'similar-products',
                    'products' => $products,
                ]
            );
        }
    }
    