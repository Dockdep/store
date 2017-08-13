<?php
    
    namespace artweb\artbox\modules\catalog\widgets;
    
    use artweb\artbox\modules\catalog\models\Brand;
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
    