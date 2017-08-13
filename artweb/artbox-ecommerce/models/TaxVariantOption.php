<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use artweb\artbox\behaviors\SaveImgBehavior;
    use artweb\artbox\language\behaviors\LanguageBehavior;
    use Yii;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;
    use yii\web\Request;
    
    /**
     * This is the model class for table "{{%tax_variant_option}}".
     *
     * @property string                 $id
     * @property integer                $tax_variant_group_id
     * @property integer                $sort
     * @property string                 $image
     * @property TaxGroup               $taxGroup
     * @property TaxGroup               $group
     * @property Product[]              $products
     * @property ProductVariant[]       $productVariants
     * * From language behavior *
     * @property TaxVariantOptionLang   $lang
     * @property TaxVariantOptionLang[] $langs
     * @property TaxVariantOptionLang   $objectLang
     * @property string                 $ownerKey
     * @property string                 $langKey
     * @property TaxVariantOptionLang[] $modelLangs
     * @property bool                   $transactionStatus
     * @method string           getOwnerKey()
     * @method void             setOwnerKey( string $value )
     * @method string           getLangKey()
     * @method void             setLangKey( string $value )
     * @method ActiveQuery      getLangs()
     * @method ActiveQuery      getLang( integer $language_id )
     * @method TaxVariantOptionLang[]    generateLangs()
     * @method void             loadLangs( Request $request )
     * @method bool             linkLangs()
     * @method bool             saveLangs()
     * @method bool             getTransactionStatus()
     * * End language behavior *
     * * From SaveImgBehavior
     * @property string|null            $imageFile
     * @property string|null            $imageUrl
     * @method string|null getImageFile( int $field )
     * @method string|null getImageUrl( int $field )
     * * End SaveImgBehavior
     */
    class TaxVariantOption extends ActiveRecord
    {
        
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
                            'directory' => 'tax_option',
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
        public static function tableName()
        {
            return '{{%tax_variant_option}}';
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [
                        'tax_variant_group_id',
                        'sort',
                    ],
                    'integer',
                ],
                [
                    [ 'tax_variant_group_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => TaxVariantGroup::className(),
                    'targetAttribute' => [ 'tax_variant_group_id' => 'id' ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'                   => Yii::t('app', 'Tax Variant Option ID'),
                'tax_variant_group_id' => Yii::t('app', 'Tax Variant Group ID'),
                'sort'                 => Yii::t('app', 'Sort'),
                'image'                => Yii::t('product', 'Image'),
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getTaxVariantGroup()
        {
            return $this->hasOne(TaxVariantGroup::className(), [ 'id' => 'tax_variant_group_id' ])
                        ->inverseOf('taxVariantOptions');
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getTaxGroup()
        {
            return $this->getTaxVariantGroup();
        }
        
        /**
         * Synonim for TaxOption::getTaxGroup()
         *
         * @see TaxOption::getTaxGroup()
         * @return \yii\db\ActiveQuery
         */
        public function getGroup()
        {
            return $this->getTaxGroup();
        }
        
        /**
         * @return ActiveQuery
         */
        public function getProductVariants()
        {
            return $this->hasMany(ProductVariant::className(), [ 'id' => 'product_variant_id' ])
                        ->viaTable('product_variant_option', [ 'option_id' => 'id' ]);
        }
    }
