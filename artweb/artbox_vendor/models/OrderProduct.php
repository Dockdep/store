<?php
    
    namespace artweb\artbox\models;
    
    use artweb\artbox\modules\catalog\models\ProductVariant;
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * Class OrderProduct
     *
     * @property int            $id
     * @property int            $order_id
     * @property int            $product_variant_id
     * @property string         $product_name
     * @property string         $name
     * @property string         $sku
     * @property double         $price
     * @property int            $count
     * @property double         $sum_cost
     * @property Order          $order
     * @property ProductVariant $productVariant
     * @package artweb\artbox\models
     */
    class OrderProduct extends ActiveRecord
    {
        
        public static function tableName()
        {
            return 'order_product';
        }
        
        public function rules()
        {
            return [
                [
                    [ 'order_id' ],
                    'required',
                ],
                //['email', 'email'],
                [
                    [
                        'product_name',
                        'name',
                        'price',
                        'count',
                        'sum_cost',
                        'product_variant_id',
                        'sku',
                    ],
                    'safe',
                ],
            ];
        }
        
        public function attributeLabels()
        {
            return [
                'product_name' => Yii::t('app', 'product_name'),
                'name'         => Yii::t('app', 'op_name'),
                'art'          => Yii::t('app', 'art'),
                'cost'         => Yii::t('app', 'cost'),
                'count'        => Yii::t('app', 'count'),
                'sum_cost'     => Yii::t('app', 'sum_cost'),
            ];
        }
        
        public function getOrder()
        {
            return $this->hasOne(Order::className(), [ 'id' => 'order_id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getProductVariant()
        {
            return $this->hasOne(ProductVariant::className(), [ 'id' => 'product_variant_id' ]);
        }
    }