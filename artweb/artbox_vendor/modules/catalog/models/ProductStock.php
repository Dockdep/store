<?php
    
    namespace artweb\artbox\modules\catalog\models;
    
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "product_stock".
     *
     * @property integer        $stock_id
     * @property integer        $quantity
     * @property integer        $product_variant_id
     * @property Product        $product
     * @property ProductVariant $productVariant
     * @property Stock          $stock
     * @property string         $title
     */
    class ProductStock extends ActiveRecord
    {
        protected $title;
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'product_stock';
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [
                        'stock_id',
                        'quantity',
                        'product_variant_id',
                    ],
                    'integer',
                ],
                [
                    [ 'title' ],
                    'required',
                ],
                [
                    [ 'product_variant_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => ProductVariant::className(),
                    'targetAttribute' => [ 'product_variant_id' => 'id' ],
                ],
                [
                    [ 'stock_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Stock::className(),
                    'targetAttribute' => [ 'stock_id' => 'id' ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'stock_id'           => 'Stock ID',
                'quantity'           => 'Количество',
                'product_variant_id' => 'Product Variant ID',
                'title'              => "Название",
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getProduct()
        {
            return $this->hasOne(Product::className(), [ 'id' => 'product_id' ])
                        ->via('variants');
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getProductVariant()
        {
            return $this->hasOne(ProductVariant::className(), [ 'id' => 'product_variant_id' ]);
        }
        
        /**
         * Get Stock title, tries to get from Stock lang
         *
         * @return string
         */
        public function getTitle(): string
        {
            if (!empty( $this->title )) {
                return $this->title;
            } elseif (!empty( $this->stock )) {
                return $this->stock->lang->title;
            } else {
                return '';
            }
        }
        
        /**
         * Set Stock title, will be saved to Stock table
         *
         * @param mixed $value
         */
        public function setTitle(string $value)
        {
            $this->title = $value;
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getStock()
        {
            return $this->hasOne(Stock::className(), [ 'id' => 'stock_id' ]);
        }
        
        /**
         * @inheritdoc
         */
        public static function primaryKey()
        {
            return [
                "stock_id",
                "product_variant_id",
            ];
        }
    }
