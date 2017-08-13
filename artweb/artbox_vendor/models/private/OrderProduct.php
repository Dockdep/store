<?php
    
    namespace artweb\artbox\models;
    
    use artweb\artbox\modules\catalog\models\ProductVariant;
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
     * @property double         $sum_count
     * @property ProductVariant $mod
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
                    [
                        'sku',
                        'count',
                        'order_id',
                    ],
                    'required',
                ],
            ];
        }
        
        public function attributeLabels()
        {
            return [
                'product_name' => 'Продукт',
                'name'         => 'Вид',
                'art'          => 'Артикул',
                'cost'         => 'Цена за один',
                'count'        => 'Кол.',
                'sum_cost'     => 'Сумма',
            ];
        }
        
        public function getMod()
        {
            return $this->hasOne(ProductVariant::className(), [ 'id' => 'product_variant_id' ]);
        }
    }