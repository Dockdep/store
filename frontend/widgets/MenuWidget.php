<?php
    namespace frontend\widgets;
    
    use artweb\artbox\ecommerce\models\Category;
    use yii\base\Widget;
    
    class MenuWidget extends Widget
    {
        /**
         * @inheritdoc
         */
        public function init()
        {
            
            parent::init();
            
        }
        
        /**
         * @return string
         */
        public function run()
        {
            $categories = Category::find()
                                  ->joinWith('lang')
                                  ->asArray()
                                  ->indexBy('id')
                                  ->orderBy('sort')
                                  ->all();
            
            foreach ($categories as $category) {
                if ($category[ 'parent_id' ] != 0) {
                    $categories[ $category[ 'parent_id' ] ][ 'children' ][] = $categories[ $category[ 'id' ] ];
                    unset($categories[ $category[ 'id' ] ]);
                }
            }
            
            return $this->render(
                '_menu_view',
                [
                    'categories' => $categories,
                ]
            );
            
        }
        
    }

?>
