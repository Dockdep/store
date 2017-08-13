<?php
    
    namespace artweb\artbox\behaviors;
    
    use yii\base\Behavior;
    use yii\base\ModelEvent;
    use yii\db\ActiveRecord;
    use yii\web\UploadedFile;
    
    /**
     * Class Save Image Behavior
     * @property ActiveRecord $owner
     * @package artweb\artbox\behaviors
     */
    class SaveImgBehavior extends Behavior
    {
        
        public $fields;
        
        public $isLanguage = false;
    
        /**
         * @var string Image path for dummy
         */
        public $dummy_path = '/storage/image-not-found.png';
        
        public function events()
        {
            return [
                ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
                ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ];
        }
        
        /**
         * @param ModelEvent $event
         */
        public function beforeSave($event)
        {
            foreach($this->fields as $field) {
                $field_name = $field[ 'name' ];
                $name = $field_name;
                if($this->isLanguage) {
                    $name = '[' . $this->owner->language_id . ']' . $name;
                }

                $image = UploadedFile::getInstance($this->owner, $name);
                
                if(empty( $image ) && $event->name == ActiveRecord::EVENT_BEFORE_UPDATE) {
                    $this->owner->$field_name = $this->owner->getOldAttribute($field_name);
                } elseif(!empty( $image )) {
                    $imgDir = \Yii::getAlias('@storage/' . $field[ 'directory' ] . '/');
                    
                    if(!is_dir($imgDir)) {
                        mkdir($imgDir, 0755, true);
                    }
                    
                    $baseName = $image->baseName;
                    
                    $iteration = 0;
                    $file_name = $imgDir . $baseName . '.' . $image->extension;
                    while(file_exists($file_name)) {
                        $baseName = $image->baseName . '_' . ++$iteration;
                        $file_name = $imgDir . $baseName . '.' . $image->extension;
                    }
                    unset( $iteration );
                    
                    $this->owner->$field_name = $baseName . '.' . $image->extension;
                    
                    $image->saveAs($file_name);
                }
            }
        }
        
        /**
         * @param int $field
         *
         * @return null|string
         */
        public function getImageFile($field = 0)
        {
            $fieldset = $this->fields[ $field ];
            $name = $fieldset[ 'name' ];
            $directory = $fieldset[ 'directory' ];
            return empty( $this->owner->$name ) ? NULL : '/storage/' . $directory . '/' . $this->owner->$name;
        }
    
        /**
         * @param int  $field
         * @param bool $dummy
         *
         * @return null|string
         */
        public function getImageUrl($field = 0, bool $dummy = true)
        {
            $fieldset = $this->fields[ $field ];
            $name = $fieldset[ 'name' ];
            $directory = $fieldset[ 'directory' ];
            return empty( $this->owner->$name ) ? ( $dummy ? $this->dummy_path : null ) : '/storage/' . $directory . '/' . $this->owner->$name;
        }

        /**
         * @param string $image
         * @return array
         */
        public function getImagesConfig($image = 'image') {
            $op = [];
            if ($this->owner->$image) {

                $op[] = [
                    'caption' => $this->owner->$image,
                    'url' => \yii\helpers\Url::to(['delimg', 'id' => $this->owner->primaryKey, 'field' =>$image ]),
                    'key' => $this->owner->primaryKey,
                    'extra' => [
                        'id' => $this->owner->primaryKey,
                    ],
                ];

            }
            return $op;
        }

    }