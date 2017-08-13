<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use artweb\artbox\behaviors\SaveImgBehavior;
    use artweb\artbox\language\behaviors\LanguageBehavior;
    use Yii;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;
    use yii\web\Request;
    
    /**
     * This is the model class for table "brand".
     *
     * @property integer     $id
     * @property string      $image
     * @property bool        $in_menu
     * @property Product[]   $products
     * @property string      $remote_id
     * * From language behavior *
     * @property BrandLang   $lang
     * @property BrandLang[] $langs
     * @property BrandLang   $objectLang
     * @property string      $ownerKey
     * @property string      $langKey
     * @property BrandLang[] $modelLangs
     * @property bool        $transactionStatus
     * @method string           getOwnerKey()
     * @method void             setOwnerKey( string $value )
     * @method string           getLangKey()
     * @method void             setLangKey( string $value )
     * @method ActiveQuery      getLangs()
     * @method ActiveQuery      getLang( integer $language_id )
     * @method BrandLang[]    generateLangs()
     * @method void             loadLangs( Request $request )
     * @method bool             linkLangs()
     * @method bool             saveLangs()
     * @method bool             getTransactionStatus()
     * * End language behavior *
     * * From SaveImgBehavior
     * @property string|null $imageFile
     * @property string|null $imageUrl
     * @method string|null getImageFile( int $field )
     * @method string|null getImageUrl( int $field )
     * * End SaveImgBehavior
     */
    class Brand extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'brand';
        }
        
        public function behaviors()
        {
            return [
                [
                    'class'  => SaveImgBehavior::className(),
                    'fields' => [
                        [
                            'name'      => 'image',
                            'directory' => 'brand',
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
                    [ 'in_menu' ],
                    'boolean',
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'    => Yii::t('product', 'Brand ID'),
                'image' => Yii::t('product', 'Image'),
            ];
        }
        
        /**
         * Get all products with current brand
         *
         * @return \yii\db\ActiveQuery
         */
        public function getProducts()
        {
            return $this->hasMany(Product::className(), [ 'brand_id' => 'id' ]);
        }
    }
