<?php
    namespace artweb\artbox\ecommerce\components;
    
    use artweb\artbox\ecommerce\models\OrderProductLog;
    use yii\base\Object;
    use yii\helpers\Json;

    class OrderProductLogger extends Object implements LoggerInterface
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
                if ($oldAttributes[ $key ] != $attribute) {
                    $data[ $key ] = [
                        'old' => $attribute,
                        'new' => $oldAttributes[ $key ],
                    ];
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
            if (!empty($data)) {
                $log = new OrderProductLog();
                $log->order_product_id = (integer) $identityId;
                $log->order_id = $params['order_id'];
                $log->user_id = (integer) \Yii::$app->user->identity->id;
                $log->data = Json::encode($data);
                
                $log->save();
            }
        }
    }