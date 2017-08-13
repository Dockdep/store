<?php
    
    namespace artweb\artbox\models;
    
    use artweb\artbox\modules\language\models\Language;
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "event_lang".
     *
     * @property integer  $event_id
     * @property integer  $language_id
     * @property string   $title
     * @property string   $body
     * @property string   $meta_title
     * @property string   $meta_description
     * @property string   $seo_text
     * @property string   $h1
     * @property string   $alias
     * @property Event    $event
     * @property Language $language
     */
    class EventLang extends ActiveRecord
    {
        
        public static function primaryKey()
        {
            return [
                'event_id',
                'language_id',
            ];
        }
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'event_lang';
        }
        
        public function behaviors()
        {
            return [
                'slug' => [
                    'class' => 'artweb\artbox\behaviors\Slug',
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [
                        'title',
                        'body',
                    ],
                    'required',
                ],
                [
                    [
                        'body',
                        'seo_text',
                    ],
                    'string',
                ],
                [
                    [
                        'title',
                        'meta_title',
                        'meta_description',
                        'h1',
                        'alias',
                    ],
                    'string',
                    'max' => 255,
                ],
                [
                    [
                        'event_id',
                        'language_id',
                    ],
                    'unique',
                    'targetAttribute' => [
                        'event_id',
                        'language_id',
                    ],
                    'message'         => 'The combination of Event ID and Language ID has already been taken.',
                ],
                [
                    [ 'event_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Event::className(),
                    'targetAttribute' => [ 'event_id' => 'id' ],
                ],
                [
                    [ 'language_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Language::className(),
                    'targetAttribute' => [ 'language_id' => 'id' ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'event_id'         => Yii::t('app', 'event_id'),
                'language_id'      => Yii::t('app', 'language_id'),
                'title'            => Yii::t('app', 'name'),
                'body'             => Yii::t('app', 'body'),
                'meta_title'       => Yii::t('app', 'meta_title'),
                'meta_description' => Yii::t('app', 'meta_description'),
                'seo_text'         => Yii::t('app', 'seo_text'),
                'h1'               => Yii::t('app', 'h1'),
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getEvent()
        {
            return $this->hasOne(Event::className(), [ 'id' => 'event_id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getLanguage()
        {
            return $this->hasOne(Language::className(), [ 'id' => 'language_id' ]);
        }
    }
