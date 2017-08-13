<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "{{%product_category}}".
     *
     * @property integer $product_id
     * @property integer $category_id
     */
    class ProductCategory extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'product_category';
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
                        'category_id',
                    ],
                    'integer',
                ],
                [
                    [ 'category_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Category::className(),
                    'targetAttribute' => [ 'category_id' => 'id' ],
                ],
                [
                    [ 'product_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Product::className(),
                    'targetAttribute' => [ 'product_id' => 'id' ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'product_id'  => Yii::t('product', 'Product'),
                'category_id' => Yii::t('product', 'Category'),
            ];
        }
        
        public function getCategory()
        {
            return $this->hasOne(Category::className(), [ 'id' => 'category_id' ]);
        }
        
        public function getProduct()
        {
            return $this->hasOne(Product::className(), [ 'id' => 'product_id' ]);
        }
    }
