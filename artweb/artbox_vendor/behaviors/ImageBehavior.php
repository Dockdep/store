<?php
    
    namespace artweb\artbox\behaviors;
    
    use yii\base\Behavior;
    use yii\base\Event;
    use yii\db\ActiveRecord;

    /**
     * Class ImageBehavior
     * @package artweb\artbox\behaviors
     */
    class ImageBehavior extends Behavior
    {
    
        /**
         * @var string column where file name is stored
         */
        public $link;
    
        /**
         * @var string directory name
         */
        public $directory;
    
        /**
         * @inheritdoc
         */
        public function events()
        {
            return [
                ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
            ];
        }
    
        /**
         * @param Event $event
         */
        public function beforeDelete($event)
        {
            $file = $this->getImageFile();
            if(file_exists($file)) {
                unlink($file);
            }
        }
    
        /**
         * Get image file path
         *
         * @return null|string
         */
        public function getImageFile()
        {
            $link = $this->link;
            return empty( $this->owner->$link ) ? NULL : \Yii::getAlias('@storage/' . $this->directory . '/' . $this->owner->$link);
        }
    
        /**
         * Get image file url
         *
         * @return null|string
         */
        public function getImageUrl()
        {
            $link = $this->link;
            return empty( $this->owner->$link ) ? NULL : '/storage/' . $this->directory . '/' . $this->owner->$link;
        }
    }