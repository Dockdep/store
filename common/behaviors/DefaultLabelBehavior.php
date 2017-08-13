<?php
    namespace common\behaviors;


    use artweb\artbox\ecommerce\models\Label;
    use yii\base\Behavior;
    use yii\db\ActiveRecord;
    
    class DefaultLabelBehavior extends Behavior
    {
        public function events()
        {
            return [
                ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            ];
        }
        
        public function beforeInsert()
        {
            $label = Label::find()
                          ->where([ 'label' => 0 ])
                          ->scalar();
            $this->owner->label = $label;
        }
    }
    