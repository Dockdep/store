<?php
    
    namespace artweb\artbox\models;
    
    use artweb\artbox\modules\language\models\Language;
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "seo_lang".
     *
     * @property integer  $seo_id
     * @property integer  $language_id
     * @property string   $title
     * @property string   $meta_description
     * @property string   $h1
     * @property string   $meta
     * @property string   $seo_text
     * @property Language $language
     * @property Seo      $seo
     */
    class SeoLang extends ActiveRecord
    {
        
        public static function primaryKey()
        {
            return [
                'seo_id',
                'language_id',
            ];
        }
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'seo_lang';
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [
                        'meta_description',
                        'seo_text',
                    ],
                    'string',
                ],
                [
                    [
                        'title',
                        'h1',
                        'meta',
                    ],
                    'string',
                    'max' => 255,
                ],
                [
                    [
                        'seo_id',
                        'language_id',
                    ],
                    'unique',
                    'targetAttribute' => [
                        'seo_id',
                        'language_id',
                    ],
                    'message'         => 'The combination of Seo ID and Language ID has already been taken.',
                ],
                [
                    [ 'language_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Language::className(),
                    'targetAttribute' => [ 'language_id' => 'id' ],
                ],
                [
                    [ 'seo_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Seo::className(),
                    'targetAttribute' => [ 'seo_id' => 'id' ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'seo_id'           => Yii::t('app', 'seo_id'),
                'language_id'      => Yii::t('app', 'language_id'),
                'title'            => Yii::t('app', 'title'),
                'meta_description' => Yii::t('app', 'meta_description'),
                'h1'               => Yii::t('app', 'h1'),
                'meta'             => Yii::t('app', 'meta'),
                'seo_text'         => Yii::t('app', 'seo_text'),
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
        public function getSeo()
        {
            return $this->hasOne(Seo::className(), [ 'id' => 'seo_id' ]);
        }
    }
