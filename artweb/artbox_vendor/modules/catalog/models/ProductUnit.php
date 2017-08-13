<?php
    
    namespace artweb\artbox\modules\catalog\models;
    
    use artweb\artbox\modules\language\behaviors\LanguageBehavior;
    use Yii;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;
    use yii\web\Request;
    
    /**
     * This is the model class for table "product_unit".
     *
     * @property integer           $id
     * @property boolean           $is_default
     * @property Category[]        $categories
     * @property ProductVariant[]  $productVariants
     * * From language behavior *
     * @property ProductUnitLang   $lang
     * @property ProductUnitLang[] $langs
     * @property ProductUnitLang   $objectLang
     * @property string            $ownerKey
     * @property string            $langKey
     * @property ProductUnitLang[] $modelLangs
     * @property bool              $transactionStatus
     * @method string           getOwnerKey()
     * @method void             setOwnerKey( string $value )
     * @method string           getLangKey()
     * @method void             setLangKey( string $value )
     * @method ActiveQuery      getLangs()
     * @method ActiveQuery      getLang( integer $language_id )
     * @method ProductUnitLang[]    generateLangs()
     * @method void             loadLangs( Request $request )
     * @method bool             linkLangs()
     * @method bool             saveLangs()
     * @method bool             getTransactionStatus()
     * * End language behavior *
     */
    class ProductUnit extends ActiveRecord
    {
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'product_unit';
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
        public function rules()
        {
            return [
                [
                    [ 'is_default' ],
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
                'id'         => Yii::t('product', 'Product Unit ID'),
                'is_default' => Yii::t('product', 'Is Default'),
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getCategories()
        {
            return $this->hasMany(Category::className(), [ 'product_unit_id' => 'id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getProductVariants()
        {
            return $this->hasMany(ProductVariant::className(), [ 'product_unit_id' => 'id' ]);
        }
    }
