<?php
    
    namespace artweb\artbox\modules\catalog\models;
    
    use artweb\artbox\behaviors\ImageBehavior;
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "product_image".
     *
     * @property integer        $id
     * @property integer        $product_id
     * @property integer        $product_variant_id
     * @property string         $image
     * @property string         $alt
     * @property string         $title
     * @property Product        $product
     * @property ProductVariant $productVariant
     */
    class ProductImage extends ActiveRecord
    {
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'product_image';
        }
        
        public function behaviors()
        {
            return [
                [
                    'class'     => ImageBehavior::className(),
                    'link'      => 'image',
                    'directory' => 'products',
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
                    [ 'product_id' ],
                    'required',
                ],
                [
                    [
                        'id',
                        'product_id',
                        'product_variant_id',
                    ],
                    'integer',
                ],
                [
                    [
                        'alt',
                        'title',
                        'image',
                    ],
                    'string',
                    'max' => 255,
                ],
                [
                    [ 'product_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Product::className(),
                    'targetAttribute' => [ 'product_id' => 'id' ],
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
                'id'                 => Yii::t('product', 'Product Image ID'),
                'product_id'         => Yii::t('product', 'Product ID'),
                'product_variant_id' => Yii::t('product', 'Product Variant ID'),
                'product'            => Yii::t('product', 'Product'),
                'product_variant'    => Yii::t('product', 'Product Variant'),
                'image'              => Yii::t('product', 'Image'),
                'alt'                => Yii::t('product', 'Alt'),
                'title'              => Yii::t('product', 'Title'),
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getProduct()
        {
            $return = $this->hasOne(Product::className(), [ 'id' => 'product_id' ]);
            return $return;
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getProductVariant()
        {
            return $this->hasOne(ProductVariant::className(), [ 'id' => 'product_variant_id' ]);
        }
    }
