<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use artweb\artbox\ecommerce\components\OrderProductLogger;
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * Class OrderProduct
     *
     * @property int               $id
     * @property int               $order_id
     * @property int               $product_variant_id
     * @property string            $booking
     * @property string            $status
     * @property boolean           $return
     * @property string            $product_name
     * @property string            $name
     * @property string            $sku
     * @property double            $price
     * @property int               $count
     * @property double            $sum_cost
     * @property Order             $order
     * @property boolean           $removed
     * @property OrderProductLog[] $logs
     * @property ProductVariant    $productVariant
     * @package artweb\artbox\ecommerce\models
     */
    class OrderProduct extends ActiveRecord
    {
        
        public static function tableName()
        {
            return 'order_product';
        }
        
        public function afterSave($insert, $changedAttributes)
        {
//            $data = OrderProductLogger::generateData($changedAttributes, $this->oldAttributes, $insert);
//            OrderProductLogger::saveData($data, $this->id, [ 'order_id' => $this->order_id ]);
            
            parent::afterSave($insert, $changedAttributes);
        }
        
        public function beforeSave($insert)
        {
            $this->price = $this->productVariant->price;
            $this->sum_cost = $this->price * $this->count;
            return parent::beforeSave($insert);
        }
        
        public function rules()
        {
            return [
                [
                    [ 'order_id' ],
                    'required',
                ],
                [
                    [
                        'return',
                        'removed',
                    ],
                    'boolean',
                ],
                [
                    [
                        'booking',
                        'status',
                    ],
                    'string',
                ],
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
                'product_name' => Yii::t('app', 'Наименование'),
                'name'         => Yii::t('app', 'op_name'),
                'art'          => Yii::t('app', 'art'),
                'cost'         => Yii::t('app', 'Сумма'),
                'count'        => Yii::t('app', 'Количество'),
                'sum_cost'     => Yii::t('app', 'Сумма'),
                'status'       => Yii::t('app', 'Статус'),
                'booking'      => Yii::t('app', 'Бронь'),
                'return'       => Yii::t('app', 'Возврат'),
                'sku'          => Yii::t('app', 'Артикул'),
                'price'        => Yii::t('app', 'Цена'),
                'order_id' => Yii::t('app', 'Id заказа'),
                'product_variant_id' => Yii::t('app', 'Id товара'),
                'id' => Yii::t('app', 'Id'),
                'removed' => Yii::t('app', 'Удален'),
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
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
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getLogs()
        {
            return $this->hasMany(OrderProductLog::className(), [ 'order_product_id' => 'id' ]);
        }
    }