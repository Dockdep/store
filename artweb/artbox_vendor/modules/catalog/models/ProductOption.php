<?php
    
    namespace artweb\artbox\modules\catalog\models;
    
    use artweb\artbox\modules\catalog\models\TaxOption;
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "product_option".
     * @property integer   $product_id
     * @property integer   $option_id
     * @property Product   $product
     * @property TaxOption $option
     */
    class ProductOption extends ActiveRecord
    {
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'product_option';
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [
                        'product_id',
                        'option_id',
                    ],
                    'required',
                ],
                [
                    [
                        'product_id',
                        'option_id',
                    ],
                    'integer',
                ],
                [
                    [ 'product_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Product::className(),
                    'targetAttribute' => [ 'product_id' => 'id' ],
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
                'product_id' => Yii::t('product', 'Product ID'),
                'option_id'  => Yii::t('product', 'Option ID'),
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getProduct()
        {
            return $this->hasOne(Product::className(), [ 'id' => 'product_id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getOption()
        {
            return $this->hasOne(TaxOption::className(), [ 'id' => 'option_id' ]);
        }
    }
