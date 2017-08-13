<?php
    namespace artweb\artbox\language\behaviors;
    
    use artweb\artbox\language\models\Language;
    use yii\base\Behavior;
    use yii\base\InvalidConfigException;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;
    use yii\db\Transaction;
    use yii\web\Request;
    
    /**
     * Class LanguageBehavior
     * @property ActiveRecord   $owner
     * @property string         $ownerKey
     * @property string         $langKey
     * @property ActiveRecord[] $langs
     * @property ActiveRecord   $lang
     */
    class LanguageBehavior extends Behavior
    {
        
        /**
         * @var ActiveRecord $objectLang Empty language model for $owner
         */
        public $objectLang;
        
        /**
         * @var ActiveRecord[] $modelLangs
         */
        public $modelLangs = [];
        
        private $ownerKey;
        
        private $langKey;
        
        /**
         * @var Transaction $transaction
         */
        private $transaction;
        
        /**
         * @var bool $transactionStatus
         */
        private $transactionStatus = false;
        
        public function events()
        {
            return [
                ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
                ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
                ActiveRecord::EVENT_AFTER_INSERT  => 'afterSave',
                ActiveRecord::EVENT_AFTER_UPDATE  => 'afterSave',
            ];
        }
        
        /**
         * Get $owner primary key to link language model
         * @return string
         */
        public function getOwnerKey():string
        {
            if(!empty( $this->ownerKey )) {
                return $this->ownerKey;
            } else {
                return $this->owner->primaryKey()[ 0 ];
            }
        }
        
        /**
         * Set which attribute to use as $owner primary key to link language model
         *
         * @param string $value
         */
        public function setOwnerKey(string $value)
        {
            $this->ownerKey = $value;
        }
        
        /**
         * Get language model attribute that is used as foreign key to $owner
         * @return string
         */
        public function getLangKey():string
        {
            if(!empty( $this->langKey )) {
                return $this->langKey;
            } else {
                $owner = $this->owner;
                return $owner::getTableSchema()->name . '_id';
            }
        }
        
        /**
         * Set which attribute to use as language model foreign key to $owner
         *
         * @param $value
         */
        public function setLangKey(string $value)
        {
            $this->langKey = $value;
        }
        
        /**
         * Additional checks to attach this behavior
         *
         * @param ActiveRecord $owner
         *
         * @throws InvalidConfigException
         */
        public function attach($owner)
        {
            if(empty( $this->objectLang )) {
                $this->objectLang = $owner::className() . 'Lang';
            } elseif(!is_string($this->objectLang)) {
                throw new InvalidConfigException('Object lang must be fully classified namespaced classname');
            }
            try {
                $this->objectLang = \Yii::createObject($this->objectLang);
            } catch(\ReflectionException $exception) {
                throw new InvalidConfigException('Object lang must be fully classified namespaced classname');
            }
            if(( !$owner instanceof ActiveRecord ) || ( !$this->objectLang instanceof ActiveRecord )) {
                throw new InvalidConfigException('Object lang must be fully classified namespaced classname');
            }
            parent::attach($owner);
        }
        
        /**
         * Get query to get all language models for $owner indexed by language_id
         * @return ActiveQuery
         */
        public function getLangs()
        {
            $objectLang = $this->objectLang;
            $owner = $this->owner;
            return $owner->hasMany($objectLang::className(), [ $this->getLangKey() => $this->getOwnerKey() ])
                         ->indexBy('language_id');
        }
        
        /**
         * Get query to get language model for $owner for language_id, default to
         * Language::getCurrent()
         *
         * @param int $language_id
         *
         * @return ActiveQuery
         */
        public function getLang(int $language_id = NULL)
        {
            if(empty( $language_id )) {
                $language_id = Language::getCurrent()->id;
            }
            $objectLang = $this->objectLang;
            $table_name = $objectLang::getTableSchema()->name;
            $owner = $this->owner;
            return $owner->hasOne($objectLang::className(), [ $this->getLangKey() => $this->getOwnerKey() ])
                         ->where([ $table_name . '.language_id' => $language_id ]);
        }
        
        /**
         * Generate language models for $owner for active languages. If $owner not new and language
         * models already inserted, models will be filled with them.
         * @return void
         */
        public function generateLangs()
        {
            $owner = $this->owner;
            $languages = Language::find()
                                 ->where([ 'status' => true ])
                                 ->orderBy([ 'id' => SORT_ASC ])
                                 ->asArray()
                                 ->column();
            $objectLang = $this->objectLang;
            $owner_key = $this->getOwnerKey();
            $langs = [];
            if(!$owner->isNewRecord) {
                $langs = $this->getLangs()
                              ->andFilterWhere([ 'language_id' => $languages ])
                              ->orderBy([ 'language_id' => SORT_ASC ])
                              ->all();
            }
            foreach($languages as $language) {
                if(!array_key_exists($language, $langs)) {
                    $langs[ $language ] = \Yii::createObject([
                        'class'             => $objectLang::className(),
                        'language_id'       => $language,
                        $this->getLangKey() => ( $owner->isNewRecord ? NULL : $owner->$owner_key ),
                    ]);
                }
            }
            $this->modelLangs = $langs;
        }
        
        /**
         * Load language models with post data.
         *
         * @param Request $request
         */
        public function loadLangs(Request $request)
        {
            foreach($request->post($this->objectLang->formName(), []) as $lang => $value) {
                if(!empty( $this->modelLangs[ $lang ] )) {
                    $this->modelLangs[ $lang ]->attributes = $value;
                    $this->modelLangs[ $lang ]->language_id = $lang;
                }
            }
        }
        
        /**
         * Link language models with $owner by setting language model language key to owner key of
         * owner
         * @return bool If $owner is new record then return false else true
         */
        public function linkLangs()
        {
            $owner = $this->owner;
            //            if($owner->isNewRecord) {
            //                return false;
            //            }
            $lang_key = $this->getLangKey();
            $owner_key = $this->getOwnerKey();
            $modelLangs = $this->modelLangs;
            foreach($modelLangs as $model_lang) {
                $model_lang->$lang_key = $owner->$owner_key;
            }
            return true;
        }
        
        /**
         * Try to save all language models to the db. Validation function is run for all models.
         * @return bool Whether all models are valid
         */
        public function saveLangs()
        {
            $success = true;
            $modelLangs = $this->modelLangs;
            foreach($modelLangs as $model_lang) {
                if($model_lang->save() === false) {
                    $success = false;
                }
            }
            return $success;
        }
        
        public function beforeSave($event)
        {
            /**
             * @var ActiveRecord $owner
             */
            $owner = $this->owner;
            $db = $owner::getDb();
            $this->transaction = $db->beginTransaction();
            if($owner->hasAttribute('remote_id') && empty( $owner->remote_id )) {
                $owner->remote_id = strval(microtime(true) * 10000);
            }
        }
        
        public function afterSave($event)
        {
            if(!empty( $this->modelLangs )) {
                if($this->linkLangs() && $this->saveLangs()) {
                    $this->transaction->commit();
                    $this->transactionStatus = true;
                } else {
                    $this->transaction->rollBack();
                    $this->transactionStatus = false;
                }
            } else {
                $this->transaction->commit();
                $this->transactionStatus = true;
            }
        }
        
        /**
         * @return bool
         */
        public function getTransactionStatus():bool
        {
            return $this->transactionStatus;
        }
    
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getLangInteractive()
        {
            $objectLang = $this->objectLang;
            $owner = $this->owner;
            return $owner->hasOne($objectLang::className(), [ $this->getLangKey() => $this->getOwnerKey() ]);
        }
    }