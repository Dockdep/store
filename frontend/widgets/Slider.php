<?php
    namespace frontend\widgets;
    
    use yii\base\Widget;
    use yii\db\ActiveQuery;
    
    class Slider extends Widget
    {
        public $title;
        
        public function init()
        {
            
            parent::init();
            
        }
        
        public function run()
        {
            $slider = \artweb\artbox\design\models\Slider::find()
                                                         ->where(
                                                             [
                                                                 \artweb\artbox\design\models\Slider::tableName(
                                                                 ) . '.title' => $this->title,
                                                             ]
                                                         )
                                                         ->joinWith(
                                                             [
                                                                 "sliderImages" => function (ActiveQuery $query) {
                                                                     return $query->orderBy('sort');
                                                                 },
                                                             ]
                                                         )
                                                         ->orderBy(
                                                             [
                                                                 'slider_image.sort' => SORT_DESC,
                                                             ]
                                                         )
                                                         ->one();
            
            if ($slider instanceof \artweb\artbox\design\models\Slider) {
                return $this->render(
                    'slider',
                    [
                        'slider' => $slider,
                        'title'  => $this->title,
                    ]
                );
                
            }
            
        }
        
    }
