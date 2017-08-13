<?php
    
    namespace backend\models;
    
    use artweb\artbox\ecommerce\models\ProductVideo;
    use yii\base\Behavior;
    use yii\base\Event;
    use yii\db\ActiveRecord;
    use yii\db\Query;
    
    class VideoMainBehavior extends Behavior
    {
        public function events()
        {
            return [
                ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
                ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ];
        }
        
        /**
         * @param Event $event
         */
        public function beforeSave($event)
        {
            /**
             * @var ProductVideo $owner
             */
            $owner = $this->owner;
            if ($owner->is_main) {
                ( new Query() )->createCommand()
                               ->update(
                                   'product_video',
                                   [
                                       'is_main' => false,
                                   ],
                                   [
                                       'is_main' => true,
                                   ]
                               )
                               ->execute();
            }
        }
    }
    