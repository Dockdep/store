<?php
    
    namespace artweb\artbox\behaviors;
    
    use artweb\artbox\models\Article;
    use artweb\artbox\modules\comment\models\CommentModel;
    use artweb\artbox\modules\catalog\models\Product;
    use artweb\artbox\widgets\Mailer;
    use yii\base\Behavior;
    use yii\base\Event;
    use yii\db\ActiveRecord;
    
    /**
     * Class NotifyBehavior
     * @property CommentModel $owner
     * @package artweb\artbox\behaviors
     */
    class NotifyBehavior extends Behavior
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
            if($owner->status == $owner::STATUS_ACTIVE) {
                $entity = $owner->entity;
                /**
                 * @var ActiveRecord $model
                 */
                $model = $entity::findOne($owner->entity_id);
                if($model != NULL) {
                    $email = '';
                    if(!empty( $owner->user )) {
                        $customer = $owner->user;
                        if(preg_match('/\S+@\S+\.\S+/', $customer->username)) {
                            $email = $customer->username;
                        } else {
                            return false;
                        }
                    }
                    $url = \Yii::$app->urlManager->getHostInfo();
                    /**
                     * @todo Change that statements
                     */
                    if($model::className() == Product::className()) {
                        $url .= '/product/' . $model->alias . '#artbox-comment';
                    } elseif($model::className() == Article::className()) {
                        $url .= '/blog/' . $model->translit . '#artbox-comment';
                    }
                    $mailer = Mailer::widget([
                        'type'    => 'comment_notify',
                        'params'  => [
                            'model'   => $model,
                            'url'     => $url,
                            'comment' => $owner,
                        ],
                        'subject' => 'Ваш комментарий опубликован',
                        'email'   => ( !empty( $customer ) ? $email : $owner->email ),
                    ]);
                    return $mailer;
                }
            }
            return false;
        }
    }