<?php
    
    namespace artweb\artbox\modules\blog\models;
    
    use artweb\artbox\modules\language\models\Language;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "blog_article_lang".
     *
     * @property integer     $id
     * @property integer     $blog_article_id
     * @property integer     $language_id
     * @property string      $title
     * @property string      $body
     * @property string      $body_preview
     * @property string      $alias
     * @property string      $meta_title
     * @property string      $meta_description
     * @property string      $h1
     * @property string      $seo_text
     * @property BlogArticle $blogArticle
     * @property Language    $language
     */
    class BlogArticleLang extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'blog_article_lang';
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
                        'blog_article_id',
                        'language_id',
                    ],
                    'required',
                ],
                [
                    [
                        'blog_article_id',
                        'language_id',
                    ],
                    'integer',
                ],
                [
                    [
                        'body',
                        'body_preview',
                    ],
                    'string',
                ],
                [
                    [
                        'title',
                        'alias',
                        'meta_title',
                        'meta_description',
                        'h1',
                        'seo_text',
                    ],
                    'string',
                    'max' => 255,
                ],
                [
                    [ 'alias' ],
                    'unique',
                ],
                [
                    [
                        'blog_article_id',
                        'language_id',
                    ],
                    'unique',
                    'targetAttribute' => [
                        'blog_article_id',
                        'language_id',
                    ],
                    'message'         => 'The combination of Blog Article ID and Language ID has already been taken.',
                ],
                [
                    [ 'blog_article_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => BlogArticle::className(),
                    'targetAttribute' => [ 'blog_article_id' => 'id' ],
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
                'id'               => 'ID',
                'blog_article_id'  => 'Blog Article ID',
                'language_id'      => 'Language ID',
                'title'            => 'Title',
                'body'             => 'Body',
                'body_preview'     => 'Body Preview',
                'alias'            => 'Alias',
                'meta_title'       => 'Meta Title',
                'meta_description' => 'Meta Description',
                'h1'               => 'H1',
                'seo_text'         => 'Seo Text',
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getBlogArticle()
        {
            return $this->hasOne(BlogArticle::className(), [ 'id' => 'blog_article_id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getLanguage()
        {
            return $this->hasOne(Language::className(), [ 'id' => 'language_id' ]);
        }
    }
