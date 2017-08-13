<?php
    
    namespace artweb\artbox\ecommerce\widgets;
    
    use artweb\artbox\ecommerce\models\Brand;
    use yii\base\Widget;
    
    class brandsCarouselWidget extends Widget
    {
        
        public function init()
        {
            parent::init();
        }
        
        public function run()
        {
            $brands = Brand::find()
                           ->with('lang')
                           ->all();
            return $this->render(
                'brandsCarousel',
                [
                    'brands' => $brands,
                ]
            );
        }
    }
    