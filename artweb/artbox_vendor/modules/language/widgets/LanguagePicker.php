<?php
    
    namespace artweb\artbox\modules\language\widgets;
    
    use artweb\artbox\modules\language\models\Language;
    use yii\bootstrap\Widget;
    
    class LanguagePicker extends Widget
    {
        
        public function init()
        {
            
        }
        
        public function run()
        {
            return $this->render('view', [
                'current' => Language::getCurrent(),
                'languages' => Language::find()
                                       ->where([
                                           '!=',
                                           'id',
                                           Language::getCurrent()->id,
                                       ])
                                       ->all(),
            ]);
        }
    }