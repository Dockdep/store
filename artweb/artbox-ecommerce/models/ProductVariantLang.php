<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use artweb\artbox\language\models\Language;
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "product_variant_lang".
     *
     * @property integer        $product_variant_id
     * @property integer        $language_id
     * @property string         $title
     * @property Language       $language
     * @property ProductVariant $productVariant
     */
    class ProductVariantLang extends ActiveRecord
    {
        
        public static function primaryKey()
        {
            return [
                'product_variant_id',
                'language_id',
            ];
        }
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'product_variant_lang';
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [ 'title' ],
                    'required',
                ],
                [
                    [ 'title' ],
                    'string',
                    'max' => 255,
                ],
                [
                    [
                        'product_variant_id',
                        'language_id',
                    ],
                    'unique',
                    'targetAttribute' => [
                        'product_variant_id',
                        'language_id',
                    ],
                    'message'         => 'The combination of Product Variant ID and Language ID has already been taken.',
                ],
                [
                    [ 'language_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Language::className(),
                    'targetAttribute' => [ 'language_id' => 'id' ],
                ],
                [
                    [ 'product_variant_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => ProductVariant::className(),
                    'targetAttribute' => [ 'product_variant_id' => 'id' ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'product_variant_id' => Yii::t('app', 'Product Variant ID'),
                'language_id'        => Yii::t('app', 'Language ID'),
                'title'              => Yii::t('app', 'Name'),
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getLanguage()
        {
            return $this->hasOne(Language::className(), [ 'id' => 'language_id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getProductVariant()
        {
            return $this->hasOne(ProductVariant::className(), [ 'id' => 'product_variant_id' ]);
        }
    }
