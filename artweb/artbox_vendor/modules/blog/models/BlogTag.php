<?php
    
    namespace artweb\artbox\modules\blog\models;
    
    use yii\db\ActiveRecord;
    use artweb\artbox\modules\language\behaviors\LanguageBehavior;
    use artweb\artbox\modules\language\models\Language;
    use yii\db\ActiveQuery;
    use yii\web\Request;
    
    /**
     * This is the model class for table "blog_tag".
     *
     * @property integer       $id
     * @property BlogArticle[] $blogArticles
     * @property BlogTagLang[] $blogTagLangs
     * @property Language[]    $languages
     * * From language behavior *
     * @property BlogTagLang   $lang
     * @property BlogTagLang[] $langs
     * @property BlogTagLang   $objectLang
     * @property string        $ownerKey
     * @property string        $langKey
     * @property BlogTagLang[] $modelLangs
     * @property bool          $transactionStatus
     * @method string           getOwnerKey()
     * @method void             setOwnerKey( string $value )
     * @method string           getLangKey()
     * @method void             setLangKey( string $value )
     * @method ActiveQuery      getLangs()
     * @method ActiveQuery      getLang( integer $language_id )
     * @method BlogTagLang[]    generateLangs()
     * @method void             loadLangs( Request $request )
     * @method bool             linkLangs()
     * @method bool             saveLangs()
     * @method bool             getTransactionStatus()
     * * End language behavior *
     */
    class BlogTag extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'blog_tag';
        }
        
        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
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
                    [ 'id' ],
                    'integer',
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id' => 'ID',
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getBlogArticles()
        {
            return $this->hasMany(BlogArticle::className(), [ 'id' => 'blog_article_id' ])
                        ->viaTable('blog_article_to_tag', [ 'blog_tag_id' => 'id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getBlogTagLangs()
        {
            return $this->hasMany(BlogTagLang::className(), [ 'blog_tag_id' => 'id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getLanguages()
        {
            return $this->hasMany(Language::className(), [ 'id' => 'language_id' ])
                        ->viaTable('blog_tag_lang', [ 'blog_tag_id' => 'id' ]);
        }
    }
