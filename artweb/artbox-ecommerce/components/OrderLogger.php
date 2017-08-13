<?php
    namespace artweb\artbox\ecommerce\components;
    
    use artweb\artbox\ecommerce\models\Delivery;
    use artweb\artbox\ecommerce\models\Label;
    use artweb\artbox\ecommerce\models\Order;
    use artweb\artbox\ecommerce\models\OrderLabelHistory;
    use artweb\artbox\ecommerce\models\OrderLog;
    use artweb\artbox\ecommerce\models\OrderPayment;
    use common\models\User;
    use yii\base\Object;
    use yii\helpers\Json;

    class OrderLogger extends Object implements LoggerInterface
    {
        /**
         * @param array $changedAttributes
         * @param array $oldAttributes
         * @param bool  $insert
         *
         * @return array
         */
        public static function generateData(array $changedAttributes, array $oldAttributes, bool $insert)
        {
            $data = [];
            foreach ($changedAttributes as $key => $attribute) {
                if ($oldAttributes[ $key ] != $attribute && $key != 'updated_at') {
                    $data[ $key ] = self::getOrderLogAttributes(
                        $key,
                        [
                            'old' => $attribute,
                            'new' => $oldAttributes[ $key ],
                        ]
                    );
                }
            }
            
            return $data;
        }
    
        /**
         * @param array $data
         * @param int   $identityId
         * @param array $params
         */
        public static function saveData(array $data, int $identityId, $params = [])
        {
            if (!empty($data) && empty($data[ 'edit_time' ])) {
                $log = new OrderLog();
                $log->order_id = (integer) $identityId;
                $log->user_id = (integer) \Yii::$app->user->identity->id;
                $log->data = Json::encode($data);
        
                $log->save();
            }
        }
    
        /**
         * @param array $attributes
         * @param       $label
         * @param int   $id
         */
        public static function saveOrderLabelHistory(array $attributes, $label, int $id)
        {
            if (!empty($attributes[ 'label' ])) {
                if ($label != (string) $attributes[ 'label' ]) {
                    $history = new OrderLabelHistory();
            
                    $history->label_id = (integer) $label;
                    $history->order_id = (integer) $id;
                    $history->user_id = (integer) \Yii::$app->user->identity->id;
            
                    if ($history->save()) {
                        \Yii::$app->session->setFlash('label_update', 'Статус заказа обновлен');
                    }
                }
            }
        }
        
        /**
         * @param string $attr
         * @param array  $values
         *
         * @return array
         * Return array in form ['old'=>'old value ...', 'new' => 'new value ...']
         */
        protected static function getOrderLogAttributes(string $attr, array $values)
        {
            if ($attr == 'deadline') {
                return [
                    'old' => empty($values[ 'old' ]) ? '' : date('d.m.Y', $values[ 'old' ]),
                    'new' => empty($values[ 'new' ]) ? '' : date('d.m.Y', $values[ 'new' ]),
                ];
            } elseif ($attr == 'reason') {
                return [
                    'old' => empty($values[ 'old' ]) ? '' : Order::REASONS[ $values[ 'old' ] ],
                    'new' => empty($values[ 'new' ]) ? '' : Order::REASONS[ $values[ 'new' ] ],
                ];
            } elseif ($attr == 'label') {
                $labels = Label::find()
                               ->with('lang')
                               ->indexBy('id')
                               ->all();
                return [
                    'old' => empty($values[ 'old' ]) ? '' : $labels[ $values[ 'old' ] ]->lang->title,
                    'new' => empty($values[ 'new' ]) ? '' : $labels[ $values[ 'new' ] ]->lang->title,
                ];
            } elseif ($attr == 'delivery') {
                $deliveries = Delivery::find()
                                      ->with('lang')
                                      ->indexBy('id')
                                      ->all();
                return [
                    'old' => empty($values[ 'old' ]) ? '' : $deliveries[ $values[ 'old' ] ]->lang->title,
                    'new' => empty($values[ 'new' ]) ? '' : $deliveries[ $values[ 'new' ] ]->lang->title,
                ];
            } elseif ($attr == 'manager_id') {
                $users = User::find()
                             ->indexBy('id')
                             ->all();
                return [
                    'old' => empty($values[ 'old' ]) ? '' : $users[ $values[ 'old' ] ]->username,
                    'new' => empty($values[ 'new' ]) ? '' : $users[ $values[ 'new' ] ]->username,
                ];
            } elseif ($attr == 'payment') {
                $payment = OrderPayment::find()
                                       ->with('lang')
                                       ->indexBy('id')
                                       ->all();
                return [
                    'old' => empty($values[ 'old' ]) ? '' : $payment[ $values[ 'old' ] ]->lang->title,
                    'new' => empty($values[ 'new' ]) ? '' : $payment[ $values[ 'new' ] ]->lang->title,
                ];
            } elseif ($attr == 'shipping_by') {
                return [
                    'old' => empty($values[ 'old' ]) ? '' : Order::SHIPPING_BY[ $values[ 'old' ] ][ 'label' ],
                    'new' => empty($values[ 'new' ]) ? '' : Order::SHIPPING_BY[ $values[ 'new' ] ][ 'label' ],
                ];
            } elseif ($attr == 'pay') {
                return [
                    'old' => ( $values[ 'old' ] == true ) ? 'Оплачен' : 'Не оплачен',
                    'new' => ( $values[ 'new' ] == true ) ? 'Оплачен' : 'Не оплачен',
                ];
            } else {
                return $values;
            }
        }
    }