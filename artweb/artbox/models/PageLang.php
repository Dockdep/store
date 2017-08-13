<?php
    
    namespace artweb\artbox\models;
    
    use artweb\artbox\language\models\Language;
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "page_lang".
     *
     * @property integer  $page_id
     * @property integer  $language_id
     * @property string   $title
     * @property string   $body
     * @property string   $meta_title
     * @property string   $meta_keywords
     * @property string   $meta_description
     * @property string   $seo_text
     * @property string   $h1
     * @property string   $alias
     * @property Language $language
     * @property Page     $page
     */
    class PageLang extends ActiveRecord
    {
        
        public static function primaryKey()
        {
            return [
                'page_id',
                'language_id',
            ];
        }
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'page_lang';
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
                        'meta_keywords',
                        'meta_description',
                        'h1',
                        'alias',
                    ],
                    'string',
                    'max' => 255,
                ],
                [
                    [
                        'page_id',
                        'language_id',
                    ],
                    'unique',
                    'targetAttribute' => [
                        'page_id',
                        'language_id',
                    ],
                    'message'         => 'The combination of Page ID and Language ID has already been taken.',
                ],
                [
                    [ 'language_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Language::className(),
                    'targetAttribute' => [ 'language_id' => 'id' ],
                ],
                [
                    [ 'page_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Page::className(),
                    'targetAttribute' => [ 'page_id' => 'id' ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'page_id'          => Yii::t('app', 'page_id'),
                'language_id'      => Yii::t('app', 'language_id'),
                'title'            => Yii::t('app', 'title'),
                'body'             => Yii::t('app', 'body'),
                'meta_title'       => Yii::t('app', 'meta_title'),
                'meta_keywords'    => Yii::t('app', 'meta_keywords'),
                'meta_description' => Yii::t('app', 'meta_description'),
                'seo_text'         => Yii::t('app', 'seo_text'),
                'h1'               => Yii::t('app', 'h1'),
                'alias'            => Yii::t('app', 'alias'),
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
        public function getPage()
        {
            return $this->hasOne(Page::className(), [ 'id' => 'page_id' ]);
        }
    }
