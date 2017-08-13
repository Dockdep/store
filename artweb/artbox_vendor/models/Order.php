<?php
    namespace artweb\artbox\models;
    
    use Yii;
    use yii\db\ActiveRecord;
    use yii\db\Expression;
    use yii\web\Session;
    use artweb\artbox\modules\catalog\models\ProductVariant;
    
    /**
     * Class Order
     *
     * @todo    Write docs and refactor
     * @package artweb\artbox\models
     * @property int    $id
     * @property int    $user_id
     * @property string $name
     * @property string $phone
     * @property string $phone2
     * @property string $email
     * @property string $adress
     * @property string $body
     * @property double $total
     * @property string $date_time
     * @property string $date_dedline
     * @property string $reserve
     * @property string $status
     * @property string $comment
     * @property int    $label
     * @property int    $pay
     * @property int    $numbercard
     * @property int    $delivery
     * @property string $declaration
     * @property string $stock
     * @property string $consignment
     * @property string $payment
     * @property string $insurance
     * @property double $amount_imposed
     * @property string $shipping_by
     * @property string $city
     */
    class Order extends ActiveRecord
    {
        
        const SCENARIO_QUICK = 'quick';
        
        private $data;
        
        public static function tableName()
        {
            return 'order';
        }
        
        public function scenarios()
        {
            $scenarios = array_merge(
                parent::scenarios(),
                [
                    self::SCENARIO_QUICK => [ 'phone' ],
                ]
            );
            return $scenarios;
        }
        
        public function rules()
        {
            return [
                [
                    [
                        'phone',
                    ],
                    'required',
                ],
                [
                    [ 'comment' ],
                    'safe',
                ],
                [
                    [ 'email' ],
                    'email',
                ],
                [
                    [ 'phone' ],
                    'match',
                    'pattern' => '/^\+38\(\d{3}\)\d{3}-\d{2}-\d{2}$/',
                    'on'      => self::SCENARIO_QUICK,
                ],
                [
                    [
                        'name',
                        'phone2',
                        'numbercard',
                        'body',
                        'declaration',
                        'stock',
                        'consignment',
                        'payment',
                        'insurance',
                        'amount_imposed',
                        'shipping_by',
                        'city',
                        'adress',
                        'total',
                        'status',
                    ],
                    'string',
                    'max' => 255,
                ],
            ];
        }
        
        public function attributeLabels()
        {
            return [
                'name'    => Yii::t('app', 'order_name'),
                'phone'   => Yii::t('app', 'order_phone'),
                'email'   => Yii::t('app', 'order_email'),
                'comment' => Yii::t('app', 'order_comment'),
            ];
        }
        
        public function beforeSave($insert)
        {
            $this->user_id = Yii::$app->user->id;
            $this->date_time = new Expression('NOW()');
            return parent::beforeSave($insert);
        }
        
        public function beforeDelete()
        {
            return parent::beforeDelete();
        }
        
        public function addBasket($product_variant_id, $count)
        {
            $session = new Session;
            $session->open();
            $data = $session[ 'basket' ];
            $i = 0;
            if (isset( $session[ 'basket' ] )) {
                foreach ($session[ 'basket' ] as $key => $basket) {
                    if ($product_variant_id == $basket[ 'id' ]) {
                        $data[ $key ][ 'count' ] += $count;
                        $session[ 'basket' ] = $data;
                        $i++;
                    }
                }
            }
            if ($i == 0) {
                $data[] = [
                    'id'    => $product_variant_id,
                    'count' => $count,
                ];
                $session[ 'basket' ] = $data;
            }
        }
        
        public function rowBasket()
        {
            $session = new Session;
            $session->open();
            $cost = 0;
            $count = 0;
            if (isset( $session[ 'basket' ] ) && count($session[ 'basket' ])) {
                foreach ($session[ 'basket' ] as $product) {
                    $count += $product[ 'count' ];
                }
            }
            
            return (object) [
                'cost'  => $cost,
                'count' => $count,
            ];
        }
        
        public function deleteBasketMod($id)
        {
            $session = new Session;
            $session->open();
            $basket = $session[ 'basket' ];
            foreach ($basket as $key => $product) {
                if ($id == $product[ 'id' ]) {
                    unset( $basket[ $key ] );
                }
            }
            $session[ 'basket' ] = $basket;
        }
        
        public function updateBasket($row)
        {
            $session = new Session;
            $session->open();
            //$data = array();
            if ($row[ 'count' ] > 0) {
                $this->data[] = [
                    'id'    => $row[ 'id' ],
                    'count' => $row[ 'count' ],
                ];
            }
            $session[ 'basket' ] = $this->data;
        }
        
        public function getBasketMods()
        {
            $session = new Session;
            $session->open();
            $products = [];
            if (empty( $session[ 'basket' ] )) {
                return [];
            }
            foreach ($session[ 'basket' ] as $product) {
                $row = ProductVariant::find()
                                     ->select(
                                         [
                                             'product_variant.*',
                                             'product.name as productName',
                                             'product.alias',
                                         ]
                                     )
                                     ->where([ 'product_variant.id' => $product[ 'id' ] ])
                                     ->leftJoin('product', 'product.id = product_variant.product_id')
                                     ->one();
                $row->count = $product[ 'count' ];
                $row->sum_cost = $product[ 'count' ] * $row->price;
                $products[] = $row;
            }
            
            return $products;
        }
        
        public function getSumCost()
        {
            $session = new Session;
            $session->open();
            $cost = 0;
            if (empty( $session[ 'basket' ] )) {
                return false;
            }
            foreach ($session[ 'basket' ] as $product) {
                $cost += ( $this->getModCost($product[ 'id' ]) * $product[ 'count' ] );
            }
            
            return $cost;
        }
        
        private function getModCost($product_variant_id)
        {
            /**
             * @var ProductVariant $mod
             */
            $mod = ProductVariant::find()
                                 ->where([ 'id' => $product_variant_id ])
                                 ->one();
            
            return $mod->price;
        }
        
        public function clearBasket()
        {
            $session = new Session;
            $session->open();
            $session[ 'basket' ] = null;
        }
        
        public function getUser()
        {
            return $this->hasOne(User::className(), [ 'id' => 'user_id' ]);
        }
        
        public function getProducts()
        {
            return $this->hasMany(OrderProduct::className(), [ 'order_id' => 'id' ]);
        }
    }