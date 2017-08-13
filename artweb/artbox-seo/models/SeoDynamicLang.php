<?php
    
    namespace artweb\artbox\seo\models;
    
    use artweb\artbox\language\models\Language;
    use Yii;
    use yii\db\ActiveRecord;


    /**
     * This is the model class for table "seo_dynamic_lang".
     *
     * @property integer    $seo_dynamic_id
     * @property integer    $language_id
     * @property string     $title
     * @property string     $meta_title
     * @property string     $h1
     * @property string     $key
     * @property string     $meta
     * @property string     $description
     * @property string     $seo_text
     * @property Language   $language
     * @property SeoDynamic $seoDynamic
     */
    class SeoDynamicLang extends ActiveRecord
    {
        
        public static function primaryKey()
        {
            return [
                'seo_dynamic_id',
                'language_id',
            ];
        }
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'seo_dynamic_lang';
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [
                        'description',
                        'seo_text',
                    ],
                    'string',
                ],
                [
                    [
                        'title',
                        'h1',
                        'key',
                        'meta',
                    ],
                    'string',
                    'max' => 255,
                ],
                [
                    [
                        'seo_dynamic_id',
                        'language_id',
                    ],
                    'unique',
                    'targetAttribute' => [
                        'seo_dynamic_id',
                        'language_id',
                    ],
                    'message'         => 'The combination of Seo Dynamic ID and Language ID has already been taken.',
                ],
                [
                    [ 'language_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Language::className(),
                    'targetAttribute' => [ 'language_id' => 'id' ],
                ],
                [
                    [ 'seo_dynamic_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => SeoDynamic::className(),
                    'targetAttribute' => [ 'seo_dynamic_id' => 'id' ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'seo_dynamic_id'   => Yii::t('app', 'seo_dynamic_id'),
                'language_id'      => Yii::t('app', 'language_id'),
                'title'            => 'title',
                'h1'               => 'h1',
                'key'              => 'key',
                'meta'             => 'meta',
                'description' => 'description',
                'seo_text'         => 'seo text',
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
        public function getSeoDynamic()
        {
            return $this->hasOne(SeoDynamic::className(), [ 'id' => 'seo_dynamic_id' ]);
        }
    }
