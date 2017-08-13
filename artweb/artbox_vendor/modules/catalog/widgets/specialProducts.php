<?php
    
    namespace artweb\artbox\modules\catalog\widgets;
    
    use artweb\artbox\modules\catalog\helpers\ProductHelper;
    use yii\base\Widget;
    use Yii;
    
    class specialProducts extends Widget
    {
        
        public $type = 'top';
        
        public $count = 8;
        
        public $title;
        
        public function init()
        {
            parent::init();
        }
        
        public function run()
        {
            $products = ProductHelper::getSpecialProducts($this->type, $this->count);
            
            if (!$this->title) {
                switch ($this->type) {
                    case 'top':
                        $this->title = Yii::t('product', 'Top products');
                        break;
                    case 'promo':
                        $this->title = Yii::t('product', 'Promo products');
                        break;
                    case 'new':
                        $this->title = Yii::t('product', 'New products');
                        break;
                }
            }
            
            return $this->render(
                'products_block',
                [
                    'title'    => $this->title,
                    'class'    => $this->type,
                    'products' => $products,
                ]
            );
        }
    }
    