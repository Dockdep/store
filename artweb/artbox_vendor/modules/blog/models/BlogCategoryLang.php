<?php
    
    namespace artweb\artbox\modules\blog\models;
    
    use yii\db\ActiveRecord;
    use artweb\artbox\modules\language\models\Language;
    
    /**
     * This is the model class for table "blog_category_lang".
     *
     * @property integer      $id
     * @property integer      $blog_category_id
     * @property integer      $language_id
     * @property string       $title
     * @property string       $alias
     * @property string       $description
     * @property string       $meta_title
     * @property string       $meta_description
     * @property string       $h1
     * @property string       $seo_text
     * @property BlogCategory $blogCategory
     * @property Language     $language
     */
    class BlogCategoryLang extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'blog_category_lang';
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
                        'blog_category_id',
                        'language_id',
                    ],
                    'required',
                ],
                [
                    [
                        'blog_category_id',
                        'language_id',
                    ],
                    'integer',
                ],
                [
                    [ 'description' ],
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
                        'blog_category_id',
                        'language_id',
                    ],
                    'unique',
                    'targetAttribute' => [
                        'blog_category_id',
                        'language_id',
                    ],
                    'message'         => 'The combination of Blog Category ID and Language ID has already been taken.',
                ],
                [
                    [ 'blog_category_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => BlogCategory::className(),
                    'targetAttribute' => [ 'blog_category_id' => 'id' ],
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
                'blog_category_id' => 'Blog Category ID',
                'language_id'      => 'Language ID',
                'title'            => 'Title',
                'alias'            => 'Alias',
                'description'      => 'Description',
                'meta_title'       => 'Meta Title',
                'meta_description' => 'Meta Description',
                'h1'               => 'H1',
                'seo_text'         => 'Seo Text',
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getBlogCategory()
        {
            return $this->hasOne(BlogCategory::className(), [ 'id' => 'blog_category_id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getLanguage()
        {
            return $this->hasOne(Language::className(), [ 'id' => 'language_id' ]);
        }
    }
