<?php
    
    namespace artweb\artbox\models;
    
    use artweb\artbox\models\Customer;
    use artweb\artbox\models\User;
    
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
     * @property User   $user
     * @package artweb\artbox\models
     */
    class Order extends \yii\db\ActiveRecord
    {
        
        public static function tableName()
        {
            return 'order';
        }
        
        public function rules()
        {
            return [
                [
                    [ 'name' ],
                    'required',
                ],
                [
                    [
                        'user_id',
                        'adress',
                        'body',
                        'total',
                        'status',
                        'email',
                        'comment',
                        'pay',
                        'date_dedline',
                        'phone',
                        'phone2',
                        'numbercard',
                        'delivery',
                        'declaration',
                        'stock',
                        'consignment',
                        'payment',
                        'insurance',
                        'amount_imposed',
                        'shipping_by',
                        'city',
                        'date_time',
                        'id',
                    ],
                    'safe',
                ],
            ];
        }
        
        public function attributeLabels()
        {
            return [
                'id'             => '№ заказа',
                'name'           => 'ФИО',
                'phone'          => 'Телефон',
                'phone2'         => 'Телефон 2',
                'adress'         => 'Адрес',
                'body'           => 'Сообщение',
                'reserve'        => 'Резерв',
                'status'         => 'Статус',
                'email'          => 'E-mail',
                'total'          => 'Сумма',
                'label'          => 'Метка',
                'comment'        => 'Комментарий менеджера',
                'date_dedline'   => 'Дедлайн',
                'numbercard'     => '№ карточки',
                'delivery'       => 'Доставка',
                'declaration'    => 'Декларация №',
                'stock'          => '№ склада',
                'consignment'    => '№ накладной',
                'payment'        => 'Способ оплаты',
                'insurance'      => 'Страховка',
                'amount_imposed' => 'Сумма наложенного',
                'shipping_by'    => 'Отправка за счет',
                'city'           => 'Город',
            ];
        }
        
        public function getUser()
        {
            return $this->hasOne(Customer::className(), [ 'id' => 'user_id' ]);
        }
        
    }