<?php
    namespace artweb\artbox\ecommerce\models;
    
    use artweb\artbox\ecommerce\components\OrderLogger;
    use artweb\artbox\models\Customer;
    use common\behaviors\DefaultLabelBehavior;
    use common\models\User;
    use Yii;
    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveRecord;
    use yii\db\Query;
    
    /**
     * Class Order
     *
     * @todo    Write docs and refactor
     * @package artweb\artbox\ecommerce\models
     * @property OrderProduct[] $products
     * @property integer        $created_at
     * @property integer        $updated_at
     * @property integer        $deleted_at
     * @property integer        $deadline
     * @property boolean        $wasted
     * @property string         $delivery_cost
     * @property integer        $reason
     * @property string         $check
     * @property string         $sms
     * @property int            $id
     * @property integer        $edit_id
     * @property integer        $edit_time
     * @property integer        $manager_id
     * @property int            $user_id
     * @property string         $name
     * @property string         $phone
     * @property string         $phone2
     * @property string         $email
     * @property string         $adress
     * @property string         $body
     * @property double         $total
     * @property string         $date_time
     * @property string         $date_dedline
     * @property string         $reserve
     * @property string         $status
     * @property string         $comment
     * @property int            $label
     * @property int            $pay
     * @property int            $numbercard
     * @property int            $delivery
     * @property string         $declaration
     * @property string         $stock
     * @property string         $consignment
     * @property string         $payment
     * @property string         $insurance
     * @property double         $amount_imposed
     * @property string         $shipping_by
     * @property string         $city
     * @property string         $deliveryString
     * @property boolean        $published
     * @property Label          $orderLabel
     * @property Delivery       $orderDelivery
     * @property OrderPayment   $orderPayment
     * @property OrderLog[]     $logs
     * @property float          $credit_sum
     * @property int            $credit_month
     * @property User           $manager
     */
    class Order extends ActiveRecord
    {
        
        const SHIPPING_BY = [
            1 => [
                'label' => 'Отправитель',
            ],
            2 => [
                'label' => 'Получатель',
            ],
        ];
        
        const REASONS = [
            1  => 'Нет товара',
            2  => 'Нет оплаты',
            3  => 'Передумал',
            4  => ' - Купил в другом месте',
            5  => ' - Не подошли условия доставки',
            6  => ' - Не подошел срок доставки',
            7  => ' - Нет денег',
            8  => ' - Купит позже',
            9  => 'Купил в другом месте',
            10 => 'Подьедет в маг.',
            11 => 'Дубль заказа.',
            12 => 'Другое',
            13 => 'Брак',
            14 => 'Отказался от Самовывоза',
            15 => 'Не приехал за Самовывозом',
            16 => 'Отменил заказ',
            17 => 'Не берет трубку',
        ];
        
        public static function tableName()
        {
            return 'order';
        }
        
        /**
         * @param array $date
         * @param array $manager
         *
         * @return array
         */
        public static function getRejectionStatistics(array $date = [], array $manager = [])
        {
            $result = [];
            foreach (self::REASONS as $id => $reason) {
                $result[ $reason ] = ( new Query() )->select(
                    [
                        'sum'   => 'SUM(total)',
                        'count' => 'COUNT(*)',
                    ]
                )
                                                    ->from(self::tableName())
                                                    ->where(
                                                        [
                                                            'reason' => $id,
                                                        ]
                                                    )
                                                    ->andFilterWhere($date)
                                                    ->andFilterWhere($manager)
                                                    ->one();
            }
            
            return $result;
        }
        
        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                [
                    'class' => TimestampBehavior::className(),
                ],
                [
                    'class' => DefaultLabelBehavior::className(),
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
                    [
                        'pay',
                        'published',
                    ],
                    'boolean',
                ],
                [
                    [
                        'shipping_by',
                        'created_at',
                        'updated_at',
                        'deleted_at',
                        'payment',
                        'reason',
                        'label',
                        'manager_id',
                        'edit_time',
                        'edit_id',
                        'delivery',
                    ],
                    'integer',
                ],
                [
                    [ 'total' ],
                    'double',
                ],
                [
                    [
                        'phone',
                    ],
                    'required',
                ],
                [
                    [
                        'comment',
                        'body',
                    ],
                    'string',
                ],
                [
                    [ 'email' ],
                    'email',
                ],
                [
                    [
                        'phone',
                        'phone2',
                    ],
                    'match',
                    'pattern' => '/^\+38\(\d{3}\)\d{3}-\d{2}-\d{2}$/',
                ],
                [
                    [
                        'credit_month',
                    ],
                    'integer',
                    'min' => 3,
                    'max' => 36,
                ],
                [
                    [
                        'credit_sum',
                    ],
                    'number',
                    'min' => 0,
                ],
                [
                    [
                        'deadline',
                        'name',
                        'numbercard',
                        'declaration',
                        'stock',
                        'consignment',
                        'insurance',
                        'amount_imposed',
                        'city',
                        'adress',
                        'status',
                        'check',
                        'sms',
                        'delivery_cost',
                    ],
                    'string',
                    'max' => 255,
                ],
            ];
        }
        
        public function afterSave($insert, $changedAttributes)
        {
            $data = OrderLogger::generateData($changedAttributes, $this->oldAttributes, $insert);
            OrderLogger::saveData($data, $this->id);
            
            OrderLogger::saveOrderLabelHistory($changedAttributes, $this->label, $this->id);
            
            parent::afterSave($insert, $changedAttributes);
        }
        
        /**
         * @inheritdoc
         */
        public function afterFind()
        {
            parent::afterFind();
            $this->deadline = !empty($this->deadline) ? date('d.m.Y', $this->deadline) : '';
            
        }
        
        /**
         * @inheritdoc
         */
        public function beforeSave($insert)
        {
            if (parent::beforeSave($insert)) {
                
                $this->convertDate();
                return true;
            }
            return false;
            
        }
        
        /**
         * Convert some date
         */
        protected function convertDate()
        {
            if (!empty($this->deadline)) {
                $date = new \DateTime();
                $date->setTimestamp(strtotime($this->deadline));
                $date->format("d.m.Y");
                $this->deadline = $date->getTimestamp();
                
            }
            
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'name'           => Yii::t('app', 'order_name'),
                'phone'          => Yii::t('app', 'order_phone'),
                'phone2'         => Yii::t('app', 'Конактный телефон 2'),
                'email'          => Yii::t('app', 'E-mail'),
                'comment'        => Yii::t('app', 'Комментарий '),
                'created_at'     => Yii::t('app', 'Дата добавления'),
                'updated_at'     => Yii::t('app', 'Дата обновления'),
                'deleted_at'     => Yii::t('app', 'Дата удаления'),
                'deadline'       => Yii::t('app', 'Дедлайн'),
                'reason'         => Yii::t('app', 'Причина'),
                'check'          => Yii::t('app', 'Чек'),
                'sms'            => Yii::t('app', 'СМС'),
                'consignment'    => Yii::t('app', 'Номер накладной'),
                'manager_id'     => Yii::t('app', 'Менеджер'),
                'delivery_cost'  => Yii::t('app', 'Стоимость доставки'),
                'published'      => Yii::t('app', 'Опубликован'),
                'label'          => Yii::t('app', 'Метка'),
                'declaration'    => Yii::t('app', 'Номер декларации'),
                'delivery'       => Yii::t('app', 'Способ доставки'),
                'total'          => Yii::t('app', 'Сумма'),
                'adress'         => Yii::t('app', 'Адрес'),
                'pay'            => Yii::t('app', 'Оплата'),
                'body'           => Yii::t('app', 'Комментарий менеджера'),
                'id'             => Yii::t('app', 'Номер'),
                'stock'          => Yii::t('app', 'Номер склада'),
                'payment'        => Yii::t('app', 'Способ оплаты'),
                'insurance'      => Yii::t('app', 'Страховка'),
                'amount_imposed' => Yii::t('app', 'Сумма наложенного'),
                'shipping_by'    => Yii::t('app', 'Отправка за счет'),
                'city'           => Yii::t('app', 'Город'),
                'numbercard'     => Yii::t('app', '№ карточки'),
                'credit_month'   => Yii::t('app', 'Количество месяцев'),
                'credit_sum'     => Yii::t('app', 'Первоначальный взнос'),
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getUser()
        {
            return $this->hasOne(Customer::className(), [ 'id' => 'user_id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getProducts()
        {
            return $this->hasMany(OrderProduct::className(), [ 'order_id' => 'id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getOrderDelivery()
        {
            return $this->hasOne(Delivery::className(), [ 'id' => 'delivery' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getOrderLabel()
        {
            return $this->hasOne(Label::className(), [ 'id' => 'label' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getOrderPayment()
        {
            return $this->hasOne(OrderPayment::className(), [ 'id' => 'payment' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getLabelsHistory()
        {
            return $this->hasMany(OrderLabelHistory::className(), [ 'order_id' => 'id' ]);
        }
        
        /**
         * @return string
         */
        public function getDeliveryString()
        {
            if (!empty($this->orderDelivery)) {
                if (!empty($this->orderDelivery->parent)) {
                    return $this->orderDelivery->parent->lang->title . ': ' . $this->orderDelivery->lang->title;
                } else {
                    return $this->orderDelivery->lang->title;
                }
            } else {
                return '';
            }
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getLogs()
        {
            return $this->hasMany(OrderLog::className(), [ 'order_id' => 'id' ]);
        }
    
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getProductLogs()
        {
            return $this->hasMany(OrderProductLog::className(), [ 'order_id' => 'id' ]);
        }
        
        /**
         * If deadline is fucked up returns true,
         * if deadline is not setted return false, like everything is ok
         *
         * @return bool
         */
        public function getWasted()
        {
            if (empty($this->deadline)) {
                return false;
            } else {
                return time() > strtotime($this->deadline);
            }
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getManager()
        {
            return $this->hasOne(User::className(), [ 'id' => 'manager_id' ]);
        }
        
        /**
         * Check if order is blocked for updating
         *
         * @return bool
         */
        public function isBlocked()
        {
            if ($this->edit_id === 0) {
                return false;
            } else {
                if ($this->edit_time + 7200 > time()) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        
        /**
         * If order products changed recount te total value
         */
        public function totalRecount()
        {
            $products = $this->products;
            $newTotal = 0;
            foreach ($products as $product) {
                if ($product->removed) {
                    continue;
                }
                $newTotal += $product->count * $product->price;
            }
            $this->total = $newTotal;
            $this->save();
        }
        
        /**
         * If exit unpublished order - delete it
         */
        public function deleteUnpublished()
        {
            /**
             * @var OrderProduct[] $products
             */
            $products = $this->products;
            foreach ($products as $product) {
                $product->delete();
            }
            
            $this->delete();
        }
    }