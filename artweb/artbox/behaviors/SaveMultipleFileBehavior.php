<?php
    
    namespace artweb\artbox\behaviors;
    
    use yii\base\Behavior;
    use yii\base\Event;
    use yii\db\ActiveRecord;
    use yii\web\UploadedFile;

    /**
     * Class SaveMultipleFileBehavior
     *
     * @todo Write validators
     *
     * @package artweb\artbox\behaviors
     */
    class SaveMultipleFileBehavior extends Behavior
    {
    
        /**
         * $owner attribute to write files
         * @var string
         */
        public $name;
    
        /**
         * Directory to store files
         * @var string
         */
        public $directory;
    
        /**
         * Column in $model where to store filename
         * @var string
         */
        public $column;
    
        /**
         * key - owner link attribute (usual ID), value - $model link attribute
         * @var array
         */
        public $links = [];
    
        /**
         * Full namespaced classname of file table
         * @var string
         */
        public $model;
        
        public function events()
        {
            return [
                ActiveRecord::EVENT_AFTER_UPDATE => 'downloadFiles',
                ActiveRecord::EVENT_AFTER_INSERT => 'downloadFiles',
            ];
        }
    
        /**
         * Save files to file table
         *
         * @param Event $event
         */
        public function downloadFiles($event)
        {
            /**
             * @var ActiveRecord $owner
             */
            $owner = $this->owner;
            $name = $this->name;
            $owner->$name = UploadedFile::getInstances($owner, $name);
            if(!empty( $files = $this->filesUpload() )) {
                $model = $this->model;
                $links = $this->links;
                $column = $this->column;
                foreach($files as $file) {
                    /**
                     * @var ActiveRecord $fileModel
                     */
                    $fileModel = new $model();
                    foreach($links as $link_owner => $link) {
                        $fileModel->$link = $owner->$link_owner;
                    }
                    $fileModel->$column = $file;
                    $fileModel->save();
                }
            }
            $this->detach();
        }
    
        /**
         * Save files to file system
         *
         * @return array
         */
        private function filesUpload()
        {
            $owner = $this->owner;
            $name = $this->name;
            $directory = $this->directory;
            $fileDir = \Yii::getAlias('@storage/' . $directory . '/');
            if(!is_dir($fileDir)) {
                mkdir($fileDir, 0755, true);
            }
            $files = [];
            /**
             * @var UploadedFile $file
             */
            foreach($owner->$name as $file) {
                $fileName = $file->baseName . '.' . $file->extension;
                $i = 0;
                while(file_exists(\Yii::getAlias($fileDir . $fileName))) {
                    $fileName = $file->baseName . '_' . ++$i . '.' . $file->extension;
                }
                $file->saveAs($fileDir . $fileName);
                $files[] = $fileName;
            }
            return $files;
        }
    }