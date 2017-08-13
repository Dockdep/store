<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use artweb\artbox\language\models\Language;
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "brand_lang".
     *
     * @property integer  $brand_id
     * @property integer  $language_id
     * @property string   $title
     * @property string   $meta_title
     * @property string   $meta_robots
     * @property string   $meta_description
     * @property string   $seo_text
     * @property string   $alias
     * @property Brand    $brand
     * @property Language $language
     */
    class BrandLang extends ActiveRecord
    {
        
        public static function primaryKey()
        {
            return [
                'brand_id',
                'language_id',
            ];
        }
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'brand_lang';
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
                    [ 'title' ],
                    'required',
                ],
                [
                    [
                        'seo_text'
                    ],
                    'string',
                ],
                [
                    [
                        'title',
                        'meta_title',
                        'meta_robots',
                        'meta_description',
                        'alias',
                    ],
                    'string',
                    'max' => 255,
                ],
                [
                    [
                        'brand_id',
                        'language_id',
                    ],
                    'unique',
                    'targetAttribute' => [
                        'brand_id',
                        'language_id',
                    ],
                    'message'         => 'The combination of Brand ID and Language ID has already been taken.',
                ],
                [
                    [ 'brand_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Brand::className(),
                    'targetAttribute' => [ 'brand_id' => 'id' ],
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
                'brand_id'         => Yii::t('app', 'Brand ID'),
                'language_id'      => Yii::t('app', 'Language ID'),
                'title'            => Yii::t('app', 'Name'),
                'meta_title'       => Yii::t('app', 'Meta Title'),
                'meta_robots'      => Yii::t('app', 'Meta Robots'),
                'meta_description' => Yii::t('app', 'Meta Desc'),
                'seo_text'         => Yii::t('app', 'Seo Text'),
                'alias'            => Yii::t('app', 'Alias')
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getBrand()
        {
            return $this->hasOne(Brand::className(), [ 'id' => 'brand_id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getLanguage()
        {
            return $this->hasOne(Language::className(), [ 'id' => 'language_id' ]);
        }
    }
