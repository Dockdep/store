<?php
    
    namespace artweb\artbox\modules\blog\models;
    
    use yii\db\ActiveRecord;
    use artweb\artbox\modules\language\behaviors\LanguageBehavior;
    use artweb\artbox\behaviors\SaveImgBehavior;
    use artweb\artbox\modules\language\models\Language;
    use yii\db\ActiveQuery;
    use yii\web\Request;
    
    /**
     * This is the model class for table "blog_category".
     *
     * @property integer            $id
     * @property integer            $sort
     * @property string             $image
     * @property integer            $parent_id
     * @property boolean            $status
     * @property BlogArticle[]      $blogArticles
     * @property BlogCategoryLang[] $blogCategoryLangs
     * @property Language[]         $languages
     * @property BlogCategory       $parent
     * * From language behavior *
     * @property BlogCategoryLang   $lang
     * @property BlogCategoryLang[] $langs
     * @property BlogCategoryLang   $objectLang
     * @property string             $ownerKey
     * @property string             $langKey
     * @property BlogCategoryLang[] $modelLangs
     * @property bool               $transactionStatus
     * @method string           getOwnerKey()
     * @method void             setOwnerKey( string $value )
     * @method string           getLangKey()
     * @method void             setLangKey( string $value )
     * @method ActiveQuery      getLangs()
     * @method ActiveQuery      getLang( integer $language_id )
     * @method BlogCategoryLang[]    generateLangs()
     * @method void             loadLangs( Request $request )
     * @method bool             linkLangs()
     * @method bool             saveLangs()
     * @method bool             getTransactionStatus()
     * * End language behavior *
     * * From SaveImgBehavior *
     * @property string|null        $imageFile
     * @property string|null        $imageUrl
     * @method string|null getImageFile( int $field )
     * @method string|null getImageUrl( int $field )
     * * End SaveImgBehavior
     */
    class BlogCategory extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'blog_category';
        }
        
        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                [
                    'class'  => SaveImgBehavior::className(),
                    'fields' => [
                        [
                            'name'      => 'image',
                            'directory' => 'blog/category',
                        ],
                    ],
                ],
                'language' => [
                    'class' => LanguageBehavior::className(),
                ],
                'Slug'     => [
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
                        'sort',
                        'parent_id',
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
                [
                    [ 'parent_id' ],
                    'default',
                    'value' => 0,
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'        => 'ID',
                'sort'      => 'Sort',
                'image'     => 'Image',
                'parent_id' => 'Parent ID',
                'status'    => 'Status',
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getBlogArticles()
        {
            return $this->hasMany(BlogArticle::className(), [ 'id' => 'blog_article_id' ])
                        ->viaTable('blog_article_to_category', [ 'blog_category_id' => 'id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getBlogCategoryLangs()
        {
            return $this->hasMany(BlogCategoryLang::className(), [ 'blog_category_id' => 'id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getLanguages()
        {
            return $this->hasMany(Language::className(), [ 'id' => 'language_id' ])
                        ->viaTable('blog_category_lang', [ 'blog_category_id' => 'id' ]);
        }
        
        public function getParent()
        {
            return $this->hasOne(BlogCategory::className(), [ 'id' => 'parent_id' ]);
        }
    }
