<?php
    
    namespace artweb\artbox\behaviors;
    
    use artweb\artbox\components\artboximage\ArtboxImageHelper;
    use yii\base\Behavior;
    use yii\db\ActiveRecord;
    use yii\helpers\Url;
    
    /**
     * Class MultipleImgBehavior
     * @todo    Write validation
     * @property ActiveRecord $owner
     * @property ActiveRecord $image
     * @property ActiveRecord[] $images
     * @package artweb\artbox\behaviors
     */
    class MultipleImgBehavior extends Behavior
    {
        
        /**
         * key - $model foreign key, value - $owner link key (usual ID)
         * @var array
         */
        public $links = [];
    
        /**
         * Will be passed to get image and get images queries
         *
         * @var array
         */
        public $conditions = [];
        
        /**
         * Full namespaced image model
         * @var string
         */
        public $model;
        
        /**
         * Image config array:
         * 'caption' - $model caption attribute
         * 'delete_action' - url to image delete action, will be passed as first argument to
         * Url::to();
         * 'id' - $model primary key
         * @var array
         */
        public $config = [];
        
        /**
         * One image query
         *
         * @return \yii\db\ActiveQuery
         */
        public function getImage()
        {
            /**
             * @var ActiveRecord $owner
             */
            $owner = $this->owner;
            $query = $owner->hasOne($this->model, $this->links);
            $conditions = $this->conditions;
            foreach($conditions as $condition) {
                $query->andWhere($condition);
            }
            return $query;
        }
    
        /**
         * All images query
         *
         * @return \yii\db\ActiveQuery
         */
        public function getImages()
        {
            /**
             * @var ActiveRecord $owner
             */
            $owner = $this->owner;
            $query = $owner->hasMany($this->model, $this->links);
            $conditions = $this->conditions;
            foreach($conditions as $left => $right) {
                $query->andWhere([$left => $right]);
            }
            return $query;
        }
    
        /**
         * Get images config array for FileInput
         *
         * @return array
         */
        public function getImagesConfig()
        {
            $op = [];
            $images = $this->getImages()->all();
            $config = $this->config;
            if(!isset( $config[ 'id' ] )) {
                return $op;
            }
            foreach($images as $image) {
                $op[] = [
                    'caption' => ( isset( $config[ 'caption' ] ) ) ? $image->{$config[ 'caption' ]} : '',
                    'url'     => ( isset( $config[ 'delete_action' ] ) ) ? Url::to([
                        $config[ 'delete_action' ],
                        'id' => $image->{$config[ 'id' ]},
                    ]) : '',
                    'key'     => $image->{$config[ 'id' ]},
                    'extra'   => [
                        'id' => $image->{$config[ 'id' ]},
                    ],
                ];
            }
            return $op;
        }
    
        /**
         * Get images HTML
         *
         * @param string $preset
         *
         * @return array
         */
        public function getImagesHTML($preset = 'admin_thumb')
        {
            $op = [];
            $images = $this->getImages()->all();
            foreach($images as $image) {
                $op[] = ArtboxImageHelper::getImage($image->imageUrl, $preset);
            }
            return $op;
        }
        
        public function getImageUrl()
        {
            $image = $this->getImage()->one();
            if(!empty($image)) {
                return $image->getImageUrl();
            } else {
                return NULL;
            }
        }
    }
    