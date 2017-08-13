<?php
    
    namespace artweb\artbox\modules\catalog\models;
    
    use artweb\artbox\modules\catalog\models\TaxOption;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "product_variant_option".
     *
     * @property integer        $product_variant_id
     * @property integer        $option_id
     * @property ProductVariant $productVariant
     * @property TaxOption      $option
     */
    class ProductVariantOption extends ActiveRecord
    {
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'product_variant_option';
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [
                        'product_variant_id',
                        'option_id',
                    ],
                    'required',
                ],
                [
                    [
                        'product_variant_id',
                        'option_id',
                    ],
                    'integer',
                ],
                [
                    [ 'product_variant_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => ProductVariant::className(),
                    'targetAttribute' => [ 'product_variant_id' => 'id' ],
                ],
                [
                    [ 'option_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => TaxOption::className(),
                    'targetAttribute' => [ 'option_id' => 'id' ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'product_variant_id' => 'Product Variant ID',
                'option_id'          => 'Option ID',
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getProductVariant()
        {
            return $this->hasOne(ProductVariant::className(), [ 'id' => 'product_variant_id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getOption()
        {
            return $this->hasOne(TaxOption::className(), [ 'id' => 'option_id' ]);
        }
    }
