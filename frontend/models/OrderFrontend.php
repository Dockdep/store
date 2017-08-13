<?php
    namespace frontend\models;
    
    use artweb\artbox\ecommerce\models\Delivery;
    use artweb\artbox\ecommerce\models\Label;
    use artweb\artbox\ecommerce\models\OrderLabelHistory;
    use artweb\artbox\ecommerce\models\OrderPayment;
    use artweb\artbox\ecommerce\models\OrderProduct;
    use artweb\artbox\ecommerce\models\ProductVariant;
    use artweb\artbox\models\Customer;
    use common\behaviors\DefaultLabelBehavior;
    use Yii;
    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;
    
    /**
     * Class Order
     *
     * @property int    $id
     * @property int    $user_id
     * @property string $name
     * @property string $phone
     * @property string $phone2
     * @property string $email
     * @property string $adress
     * @property string $body
     * @property double $total
     * @property string $created_at
     * @property string $updated_at
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
     * @property float  $credit_sum
     * @property int    $credit_month
     * @property Label  $labelModel
     */
    class OrderFrontend extends ActiveRecord
    {
        
        const SCENARIO_QUICK = 'quick';
        const SCENARIO_FAST = 'fast';
        const SCENARIO_CUSTOMER = 'customer';
        const SCENARIO_GUEST = 'guest';
        const SCENARIO_INFO = 'info';
        
        //        public $subscribe;
        
        //        public $notRegister;
        
        public $confirm;
        public $variant_id;
        
        public static function tableName()
        {
            return 'order';
        }
        
        public function beforeSave($insert)
        {
            $this->published = true;
            return parent::beforeSave($insert);
        }
        
        public function afterSave($insert, $changedAttributes)
        {
            if ($insert) {
                $history = new OrderLabelHistory();
    
                $history->label_id = (integer) $this->label;
                $history->order_id = (integer) $this->id;
    
                $history->save();
            }
            parent::afterSave($insert, $changedAttributes);
        }
    
        public function scenarios()
        {
            $scenarios = array_merge(
                parent::scenarios(),
                [
                    self::SCENARIO_FAST => [
                        'variant_id',
                        'name',
                        'phone',
                    ],
                    self::SCENARIO_QUICK    => [ 'phone' ],
                    self::SCENARIO_CUSTOMER => [
                        'phone',
                        'email',
                        'adress',
                        'comment',
                        'city',
                        'delivery',
                        'payment',
                        'confirm',
                        'label',
                        'credit_sum',
                        'credit_month',
                    ],
                    self::SCENARIO_GUEST    => [
                        'name',
                        //                        'subscribe',
                        //                        'notRegister',
                        'phone',
                        'email',
                        'adress',
                        'comment',
                        'city',
                        'delivery',
                        'payment',
                        'confirm',
                        'credit_sum',
                        'credit_month',
                    ],
                    self::SCENARIO_INFO     => [
                        'id',
                    ],
                ]
            );
            return $scenarios;
        }
        
        public function behaviors()
        {
            return [
                [
                    'class'              => TimestampBehavior::className(),
                    'createdAtAttribute' => 'created_at',
                    'updatedAtAttribute' => false,
                ],
                [
                    'class' => DefaultLabelBehavior::className(),
                ],
            ];
        }
        
        public function rules()
        {
            return [
                [
                    [
                        'id',
                    ],
                    'integer',
                ],
                [
                    [
                        'vaiant_id',
                    ],
                    'integer',
                    'on' => self::SCENARIO_FAST,
                ],
                [
                    [
                        'id',
                    ],
                    'required',
                ],
                [
                    [
                        'delivery',
                        'payment',
                    ],
                    'required',
                    'enableClientValidation' => false,
                ],
                [
                    [
                        'name',
                        'phone',
                        'confirm',
                    ],
                    'required',
                ],
                [
                    [
                        'variant_id',
                    ],
                    'required',
                    'on' => self::SCENARIO_FAST,
                ],
                [
                    [
                        'variant_id',
                    ],
                    'exist',
                    'targetClass' => ProductVariant::className(),
                    'targetAttribute' => 'id',
                    'allowArray' => false,
                    'filter' => function($query) {
                        /**
                         * @var ActiveQuery $query
                         */
                        $query->andWhere(['>', 'stock', 0]);
                    }
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
                    [ 'email' ],
                    'email',
                ],
                [
                    [
                        'adress',
                        'email',
                        'city',
                        'name',
                    ],
                    'string',
                    'max' => 255,
                ],
                [
                    [ 'comment' ],
                    'string',
                ],
                [
                    [ 'credit_month' ],
                    'integer',
                    'min' => 3,
                    'max' => 36,
                ],
                [
                    [ 'credit_sum' ],
                    'number',
                    'min' => 0,
                ],
                [
                    [
                        'credit_month',
                        'credit_sum',
                    ],
                    'filter',
                    'filter' => function ($value) {
                        if ($this->payment == 10) {
                            return $value;
                        } else {
                            return null;
                        }
                    },
                ],
                [
                    [ 'delivery' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Delivery::className(),
                    'targetAttribute' => [ 'delivery' => 'id' ],
                    'filter'          => function ($query) {
                        /**
                         * @var ActiveQuery $query
                         */
                        $query->leftJoin(
                            'order_delivery as children_delivery',
                            'order_delivery.id = children_delivery.parent_id'
                        )
                              ->where([ 'order_delivery.id' => $this->delivery ])
                              ->groupBy('order_delivery.id')
                              ->having('count(children_delivery.id) = 0');
                    },
                ],
                [
                    [ 'payment' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => OrderPayment::className(),
                    'targetAttribute' => [ 'payment' => 'id' ],
                    'filter'          => function ($query) {
                        /**
                         * @var ActiveQuery $query
                         */
                        $query->where([ 'status' => OrderPayment::ACTIVE ]);
                    },
                ],
                //                [
                //                    [
                //                        'subscribe',
                //                        'notRegister',
                //                    ],
                //                    'boolean',
                //                ],
                [
                    [
                        'confirm',
                    ],
                    'in',
                    'range'      => [ 1 ],
                    'allowArray' => false,
                ],
                //                [
                //                    [
                //                        'phone',
                //                    ],
                //                    'unique',
                //                    'targetClass'     => Customer::className(),
                //                    'targetAttribute' => 'phone',
                //                    'message'         => \Yii::t(
                //                        'app',
                //                        'Данный номер телефона пренадлежит одному из пользователей, либо авторизируйтесь, либо укажите другой номер телефона'
                //                    ),
                //                    'when'            => function ($model) {
                //                        /**
                //                         * @var OrderFrontend $model
                //                         */
                //                        if (!\Yii::$app->user->isGuest && $model->phone == \Yii::$app->user->identity->phone) {
                //                            return false;
                //                        } else {
                //                            return true;
                //                        }
                //                    },
                //                ],
                [
                    [
                        'email',
                    ],
                    'unique',
                    'targetClass'     => Customer::className(),
                    'targetAttribute' => 'email',
                    'message'         => \Yii::t(
                        'app',
                        'Данный email пренадлежит одному из пользователей, либо <a href="#" data-form="register">авторизируйтесь</a>, либо укажите другой email'
                    ),
                    'when'            => function ($model) {
                        /**
                         * @var OrderFrontend $model
                         */
                        if (!\Yii::$app->user->isGuest && $model->email == \Yii::$app->user->identity->email) {
                            return false;
                        } else {
                            return true;
                        }
                    },
                ],
            ];
        }
        
        public function attributeLabels()
        {
            return [
                'name'         => Yii::t('app', 'orderf1'),
                'phone'        => Yii::t('app', 'orderf2'),
                'phone2'       => Yii::t('app', 'orderf3'),
                'email'        => Yii::t('app', 'orderf4'),
                'city'         => Yii::t('app', 'orderf5'),
                'adress'       => Yii::t('app', 'orderf6'),
                'comment'      => Yii::t('app', 'orderf7'),
                //                'subscribe' => Yii::t('app', 'orderf8'),
                //                'notRegister'  => Yii::t('app', 'orderf9'),
                'confirm'      => Yii::t('app', 'orderf10'),
                'credit_month' => Yii::t('app', 'orderf11'),
                'credit_sum'   => Yii::t('app', 'orderf12'),
            ];
        }
        
        public function getDelivery()
        {
            return $this->hasOne(Delivery::className(), [ 'id' => 'delivery' ]);
        }
        
        public function getUser()
        {
            return $this->hasOne(Customer::className(), [ 'id' => 'user_id' ]);
        }
        
        public function getProducts()
        {
            return $this->hasMany(OrderProduct::className(), [ 'order_id' => 'id' ]);
        }
        
        public function getLabelModel()
        {
            return $this->hasOne(Label::className(), [ 'id' => 'label' ]);
        }
    }
    