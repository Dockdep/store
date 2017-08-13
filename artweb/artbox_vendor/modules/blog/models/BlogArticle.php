<?php
    
    namespace artweb\artbox\modules\blog\models;
    
    use artweb\artbox\behaviors\SaveImgBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveRecord;
    use artweb\artbox\modules\language\behaviors\LanguageBehavior;
    use artweb\artbox\modules\language\models\Language;
    use artweb\artbox\modules\catalog\models\Product;
    use yii\db\ActiveQuery;
    use yii\web\Request;
    
    /**
     * This is the model class for table "blog_article".
     *
     * @property integer           $id
     * @property string            $image
     * @property integer           $created_at
     * @property integer           $updated_at
     * @property integer           $deleted_at
     * @property integer           $sort
     * @property boolean           $status
     * @property integer           $author_id
     * @property BlogArticleLang[] $blogArticleLangs
     * @property Language[]        $languages
     * @property BlogArticle[]     $relatedBlogArticles
     * @property BlogArticle[]     $blogArticles
     * @property BlogCategory[]    $blogCategories
     * @property Product[]         $products
     * @property BlogTag[]         $blogTags
     * * * From language behavior *
     * @property BlogArticleLang   $lang
     * @property BlogArticleLang[] $langs
     * @property BlogArticleLang   $objectLang
     * @property string            $ownerKey
     * @property string            $langKey
     * @property BlogArticleLang[] $modelLangs
     * @property bool              $transactionStatus
     * @method string           getOwnerKey()
     * @method void             setOwnerKey( string $value )
     * @method string           getLangKey()
     * @method void             setLangKey( string $value )
     * @method ActiveQuery      getLangs()
     * @method ActiveQuery      getLang( integer $language_id )
     * @method BlogArticleLang[]    generateLangs()
     * @method void             loadLangs( Request $request )
     * @method bool             linkLangs()
     * @method bool             saveLangs()
     * @method bool             getTransactionStatus()
     * * End language behavior *
     * * From SaveImgBehavior
     * @property string|null       $imageFile
     * @property string|null       $imageUrl
     * @method string|null getImageFile( int $field )
     * @method string|null getImageUrl( int $field )
     * * End SaveImgBehavior
     */
    class BlogArticle extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'blog_article';
        }
        
        public function behaviors()
        {
            return [
                [
                    'class' => TimestampBehavior::className(),
                ],
                [
                    'class'  => SaveImgBehavior::className(),
                    'fields' => [
                        [
                            'name'      => 'image',
                            'directory' => 'blog/article',
                        ],
                    ],
                ],
                'language' => [
                    'class' => LanguageBehavior::className(),
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
                        'created_at',
                        'updated_at',
                        'deleted_at',
                        'sort',
                        'author_id',
                    ],
                    'integer',
                ],
                [
                    [ 'status' ],
                    'boolean',
                ],
                [
                    [ 'image' ],
                    'string',
                    'max' => 255,
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'         => 'ID',
                'image'      => 'Image',
                'created_at' => 'Created At',
                'updated_at' => 'Updated At',
                'deleted_at' => 'Deleted At',
                'sort'       => 'Sort',
                'status'     => 'Status',
                'author_id'  => 'Author ID',
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getBlogArticleLangs()
        {
            return $this->hasMany(BlogArticleLang::className(), [ 'blog_article_id' => 'id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getLanguages()
        {
            return $this->hasMany(Language::className(), [ 'id' => 'language_id' ])
                        ->viaTable('blog_article_lang', [ 'blog_article_id' => 'id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getRelatedBlogArticles()
        {
            return $this->hasMany(BlogArticle::className(), [ 'id' => 'related_blog_article_id' ])
                        ->viaTable('blog_article_to_article', [ 'blog_article_id' => 'id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getBlogArticles()
        {
            return $this->hasMany(BlogArticle::className(), [ 'id' => 'blog_article_id' ])
                        ->viaTable('blog_article_to_article', [ 'related_blog_article_id' => 'id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getBlogCategories()
        {
            return $this->hasMany(BlogCategory::className(), [ 'id' => 'blog_category_id' ])
                        ->viaTable('blog_article_to_category', [ 'blog_article_id' => 'id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getProducts()
        {
            return $this->hasMany(Product::className(), [ 'id' => 'product_id' ])
                        ->viaTable('blog_article_to_product', [ 'blog_article_id' => 'id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getBlogTags()
        {
            return $this->hasMany(BlogTag::className(), [ 'id' => 'blog_tag_id' ])
                        ->viaTable('blog_article_to_tag', [ 'blog_article_id' => 'id' ]);
        }
    }
