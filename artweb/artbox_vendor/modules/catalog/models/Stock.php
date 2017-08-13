<?php
    
    namespace artweb\artbox\modules\catalog\models;
    
    use artweb\artbox\modules\language\behaviors\LanguageBehavior;
    use Yii;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;
    use yii\web\Request;
    
    /**
     * This is the model class for table "stock".
     *
     * @property integer          $id
     * @property ProductStock[]   $productStocks
     * @property ProductVariant[] $productVariants
     * @property Product[]        $products
     * * From language behavior *
     * @property StockLang        $lang
     * @property StockLang[]      $langs
     * @property StockLang        $objectLang
     * @property string           $ownerKey
     * @property string           $langKey
     * @property StockLang[]      $modelLangs
     * @property bool             $transactionStatus
     * @method string           getOwnerKey()
     * @method void             setOwnerKey( string $value )
     * @method string           getLangKey()
     * @method void             setLangKey( string $value )
     * @method ActiveQuery      getLangs()
     * @method ActiveQuery      getLang( integer $language_id )
     * @method StockLang[]    generateLangs()
     * @method void             loadLangs( Request $request )
     * @method bool             linkLangs()
     * @method bool             saveLangs()
     * @method bool             getTransactionStatus()
     * * End language behavior *
     */
    class Stock extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'stock';
        }
        
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
        public function attributeLabels()
        {
            return [
                'id' => Yii::t('product', 'Stock ID'),
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getProductStocks()
        {
            return $this->hasMany(ProductStock::className(), [ 'stock_id' => 'id' ]);
        }
        
        /**
         * @return ActiveQuery
         */
        public function getProductVariants()
        {
            return $this->hasMany(ProductVariant::className(), [ 'id' => 'product_variant_id' ])
                        ->via('productStocks');
        }
        
        /**
         * @return ActiveQuery
         */
        public function getProducts()
        {
            return $this->hasMany(Product::className(), [ 'id' => 'product_id' ])
                        ->via('productVariants');
        }
    }
