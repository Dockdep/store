<?php
    
    namespace artweb\artbox\models;
    
    use artweb\artbox\modules\catalog\models\ProductVariant;
    use yii\db\ActiveRecord;
    
    class orderProduct extends ActiveRecord
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