<?php
    
    namespace artweb\artbox\models;
    
    use artweb\artbox\modules\language\behaviors\LanguageBehavior;
    use artweb\artbox\behaviors\SaveImgBehavior;
    use Yii;
    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;
    use yii\web\Request;
    
    /**
     * This is the model class for table "articles".
     *
     * @property integer       $id
     * @property integer       $created_at
     * @property string        $image
     * * From language behavior *
     * @property ArticleLang   $lang
     * @property ArticleLang[] $langs
     * @property ArticleLang   $objectLang
     * @property string        $ownerKey
     * @property string        $langKey
     * @property ArticleLang[] $modelLangs
     * @property bool          $transactionStatus
     * @method string           getOwnerKey()
     * @method void             setOwnerKey( string $value )
     * @method string           getLangKey()
     * @method void             setLangKey( string $value )
     * @method ActiveQuery      getLangs()
     * @method ActiveQuery      getLang( integer $language_id )
     * @method ArticleLang[]   generateLangs()
     * @method void             loadLangs( Request $request )
     * @method bool             linkLangs()
     * @method bool             saveLangs()
     * @method bool             getTransactionStatus()
     * * End language behavior *
     * * From SaveImgBehavior
     * @property string|null   $imageFile
     * @property string|null   $imageUrl
     * @method string|null getImageFile( int $field )
     * @method string|null getImageUrl( int $field )
     * * End SaveImgBehavior
     */
    class Article extends ActiveRecord
    {
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'article';
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
                            'directory' => 'article',
                        ],
                    ],
                ],
                'language' => [
                    'class' => LanguageBehavior::className(),
                ],
                [
                    'class'              => TimestampBehavior::className(),
                    'updatedAtAttribute' => false,
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
                    [ 'created_at' ],
                    'safe',
                ],
                [
                    [ 'created_at' ],
                    'filter',
                    'filter' => function($value) {
                        return strtotime($value) ? : time();
                    },
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'         => Yii::t('app', 'ID'),
                'created_at' => Yii::t('app', 'Date'),
                'image'      => Yii::t('app', 'Image'),
                'imageUrl'   => Yii::t('app', 'Image'),
            ];
        }
    }
