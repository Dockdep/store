<?php
    
    namespace artweb\artbox\models;
    
    use artweb\artbox\modules\language\models\Language;
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "article_lang".
     * @property integer  $article_id
     * @property integer  $language_id
     * @property string   $title
     * @property string   $body
     * @property string   $meta_title
     * @property string   $meta_keywords
     * @property string   $meta_description
     * @property string   $seo_text
     * @property string   $h1
     * @property string   $body_preview
     * @property string   $alias
     * @property Article $article
     * @property Language $language
     */
    class ArticleLang extends ActiveRecord
    {
        
        public static function primaryKey()
        {
            return [
                'article_id',
                'language_id',
            ];
        }
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'article_lang';
        }
        
        public function behaviors()
        {
            return [
                'slug' => [
                    'class'         => 'artweb\artbox\behaviors\Slug',
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
                        'body_preview',
                        'alias',
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
                    ],
                    'string',
                    'max' => 255,
                ],
                [
                    [
                        'article_id',
                        'language_id',
                    ],
                    'unique',
                    'targetAttribute' => [
                        'article_id',
                        'language_id',
                    ],
                    'message'         => 'The combination of Article ID and Language ID has already been taken.',
                ],
                [
                    [ 'article_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Article::className(),
                    'targetAttribute' => [ 'article_id' => 'id' ],
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
                'article_id'      => Yii::t('app', 'lang-Article ID'),
                'language_id'      => Yii::t('app', 'lang-Language ID'),
                'title'            => Yii::t('app', 'lang-Title'),
                'body'             => Yii::t('app', 'lang-Body'),
                'meta_title'       => Yii::t('app', 'lang-Meta Title'),
                'meta_keywords'    => Yii::t('app', 'lang-Meta Keywords'),
                'meta_description' => Yii::t('app', 'lang-Meta Description'),
                'seo_text'         => Yii::t('app', 'lang-Seo Text'),
                'h1'               => Yii::t('app', 'lang-H1'),
                'body_preview'     => Yii::t('app', 'lang-Body Preview'),
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getArticle()
        {
            return $this->hasOne(Article::className(), [ 'id' => 'article_id' ])
                        ->inverseOf('langs');
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getLanguage()
        {
            return $this->hasOne(Language::className(), [ 'id' => 'language_id' ]);
        }
    }
