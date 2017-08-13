<?php
    
    namespace artweb\artbox\models;
    
    use artweb\artbox\modules\language\models\Language;
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "service_lang".
     *
     * @property integer  $service_id
     * @property integer  $language_id
     * @property string   $title
     * @property string   $body
     * @property string   $seo_text
     * @property string   $meta_title
     * @property string   $meta_description
     * @property string   $h1
     * @property Language $language
     * @property Service  $service
     */
    class ServiceLang extends ActiveRecord
    {
        
        public static function primaryKey()
        {
            return [
                'service_id',
                'language_id',
            ];
        }
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'service_lang';
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
                    ],
                    'string',
                    'max' => 255,
                ],
                [
                    [
                        'service_id',
                        'language_id',
                    ],
                    'unique',
                    'targetAttribute' => [
                        'service_id',
                        'language_id',
                    ],
                    'message'         => 'The combination of Service ID and Language ID has already been taken.',
                ],
                [
                    [ 'language_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Language::className(),
                    'targetAttribute' => [ 'language_id' => 'id' ],
                ],
                [
                    [ 'service_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Service::className(),
                    'targetAttribute' => [ 'service_id' => 'id' ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'service_id'       => Yii::t('app', 'service_id'),
                'language_id'      => Yii::t('app', 'language_id'),
                'title'            => Yii::t('app', 'name'),
                'body'             => Yii::t('app', 'body'),
                'seo_text'         => Yii::t('app', 'seo_text'),
                'meta_title'       => Yii::t('app', 'meta_title'),
                'meta_description' => Yii::t('app', 'meta_description'),
                'h1'               => Yii::t('app', 'h1'),
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getLanguage()
        {
            return $this->hasOne(Language::className(), [ 'id' => 'language_id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getService()
        {
            return $this->hasOne(Service::className(), [ 'id' => 'service_id' ]);
        }
    }
