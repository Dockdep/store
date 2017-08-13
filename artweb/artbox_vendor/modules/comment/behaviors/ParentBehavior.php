<?php
    namespace artweb\artbox\modules\comment\behaviors;
    
    use artweb\artbox\modules\comment\models\CommentModel;
    use yii\base\Behavior;
    use yii\base\Event;
    use yii\db\ActiveRecord;
    
    class ParentBehavior extends Behavior
    {
        
        public function events()
        {
            return [
                ActiveRecord::EVENT_AFTER_VALIDATE => 'afterValidate',
            ];
        }
        
        /**
         * @param Event $event
         */
        public function afterValidate($event)
        {
            /**
             * @var CommentModel $owner
             */
            $owner = $this->owner;
            if(!empty( $owner->artbox_comment_pid )) {
                /**
                 * @var CommentModel $parent
                 */
                $parent = CommentModel::find()
                                      ->where([ 'artbox_comment_id' => $owner->artbox_comment_pid ])
                                      ->one();
                if(!empty( $parent->artbox_comment_pid )) {
                    $owner->related_id = $owner->artbox_comment_pid;
                    $owner->artbox_comment_pid = $parent->artbox_comment_pid;
                }
            }
        }
    }