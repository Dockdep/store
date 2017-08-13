<?php
    
    namespace artweb\artbox\behaviors;
    
    use yii\base\Behavior;
    use yii\base\Event;
    use yii\db\ActiveRecord;
    
    /**
     * Class ImageBehavior
     *
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
         * @var string Image path for dummy
         */
        public $dummy_path = '/storage/image-not-found.png';
        
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
            if (file_exists($file)) {
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
            return empty( $this->owner->$link ) ? null : \Yii::getAlias(
                '@storage/' . $this->directory . '/' . $this->owner->$link
            );
        }
    
        /**
         * Get image file url
         *
         * @param bool $dummy
         *
         * @return null|string
         */
        public function getImageUrl(bool $dummy = true)
        {
            $link = $this->link;
            return empty( $this->owner->$link ) ? ( $dummy ? $this->dummy_path : null ) : '/storage/' . $this->directory . '/' . $this->owner->$link;
        }
    }