<?php
    
    namespace artweb\artbox\behaviors;
    
    use artweb\artbox\models\Article;
    use artweb\artbox\modules\comment\models\CommentModel;
    use artweb\artbox\modules\catalog\models\Product;
    use yii\base\Behavior;
    use yii\base\Event;
    use yii\db\ActiveRecord;
    
    /**
     * Class RatingBehavior
     * @property CommentModel $owner
     * @package artweb\artbox\behaviors
     */
    class RatingBehavior extends Behavior
    {
        
        public function events()
        {
            return [
                ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            ];
        }
        
        public function afterUpdate($event)
        {
            /**
             * @var Event        $event
             * @var CommentModel $owner
             */
            $owner = $this->owner;
            if($owner->entity == Product::className() || $owner->entity == Article::className()) {
                $entity = $owner->entity;
                $model = $entity::findOne($owner->entity_id);
                if($model != NULL) {
                    $model->recalculateRating();
                }
            }
        }
    }