<?php
    
    namespace artweb\artbox\models;
    
    use artweb\artbox\modules\language\models\Language;
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "seo_category_lang".
     *
     * @property integer     $seo_category_id
     * @property integer     $language_id
     * @property string      $title
     * @property Language    $language
     * @property SeoCategory $seoCategory
     */
    class SeoCategoryLang extends ActiveRecord
    {
        
        public static function primaryKey()
        {
            return [
                'seo_category_id',
                'language_id',
            ];
        }
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'seo_category_lang';
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [ 'title' ],
                    'string',
                    'max' => 255,
                ],
                [
                    [
                        'seo_category_id',
                        'language_id',
                    ],
                    'unique',
                    'targetAttribute' => [
                        'seo_category_id',
                        'language_id',
                    ],
                    'message'         => 'The combination of Seo Category ID and Language ID has already been taken.',
                ],
                [
                    [ 'language_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Language::className(),
                    'targetAttribute' => [ 'language_id' => 'id' ],
                ],
                [
                    [ 'seo_category_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => SeoCategory::className(),
                    'targetAttribute' => [ 'seo_category_id' => 'id' ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'seo_category_id' => Yii::t('app', 'seo_category_id'),
                'language_id'     => Yii::t('app', 'language_id'),
                'title'           => Yii::t('app', 'name'),
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
        public function getSeoCategory()
        {
            return $this->hasOne(SeoCategory::className(), [ 'id' => 'seo_category_id' ]);
        }
    }
