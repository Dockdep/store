<?php
    
    namespace artweb\artbox\behaviors;
    
    use yii;
    use yii\base\Behavior;
    use yii\db\ActiveRecord;
    use dosamigos\transliterator\TransliteratorHelper;
    
    class Slug extends Behavior
    {
        
        /**
         * @var string
         */
        public $inAttribute = 'title';
        
        /**
         * @var string
         */
        public $outAttribute = 'alias';
        
        /**
         * @var bool
         */
        public $translit = true;
        
        /**
         * @inheritdoc
         */
        public function events()
        {
            return [
                ActiveRecord::EVENT_BEFORE_INSERT => 'getSlug',
                ActiveRecord::EVENT_BEFORE_UPDATE => 'getSlug',
            ];
        }
        
        /**
         * Generate slug
         *
         * @param yii\base\Event $event
         *
         * @return void
         */
        public function getSlug($event)
        {
            if(!empty( $this->owner->{$this->inAttribute} )) {
                if(empty( $this->owner->{$this->outAttribute} )) {
                    $this->owner->{$this->outAttribute} = $this->generateSlug($this->owner->{$this->inAttribute});
                } else {
                    $this->owner->{$this->outAttribute} = $this->generateSlug($this->owner->{$this->outAttribute});
                }
            }
        }
        
        /**
         * @param string $slug
         *
         * @return string
         */
        private function generateSlug($slug)
        {
            $slug = $this->slugify($slug);
            if($this->checkUniqueSlug($slug)) {
                return $slug;
            } else {
                for($suffix = 2; !$this->checkUniqueSlug($new_slug = $slug . '-' . $suffix); $suffix++) {
                }
                return $new_slug;
            }
        }
        
        /**
         * @param string $slug
         *
         * @return string
         */
        private function slugify($slug)
        {
            if($this->translit) {
                return yii\helpers\Inflector::slug(TransliteratorHelper::process($slug), '-', true);
            } else {
                return $this->slug($slug, '-', true);
            }
        }
        
        /**
         * @param string $string
         * @param string $replacement
         * @param bool   $lowercase
         *
         * @return string
         */
        private function slug($string, $replacement = '-', $lowercase = true)
        {
            $string = preg_replace('/[^\p{L}\p{Nd}]+/u', $replacement, $string);
            $string = trim($string, $replacement);
            return $lowercase ? strtolower($string) : $string;
        }
        
        /**
         * Check whether current slug is unique
         *
         * @param string $slug
         *
         * @return bool
         */
        private function checkUniqueSlug($slug)
        {
            /**
             * @var ActiveRecord $owner
             */
            $owner = $this->owner;
            $query = $owner->find()
                           ->where([
                               $this->outAttribute => $slug,
                           ]);
            if(!$owner->isNewRecord) {
                $pks = $owner->primaryKey();
                if(!empty( $pks )) {
                    $pk_rules = [ 'and' ];
                    foreach($pks as $pk) {
                        $pk_rules[] = [ $pk => $owner->$pk ];
                    }
                    $query->andWhere([
                        'not',
                        $pk_rules,
                    ]);
                }
            }
            return !$query->exists();
        }
        
    }