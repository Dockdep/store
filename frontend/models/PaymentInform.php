<?php
    
    namespace frontend\models;
    
    use artweb\artbox\models\Customer;
    use yii\base\Model;
    
    class PaymentInform extends Model
    {
        public $orderId;
        
        public $name;
        
        public $file;
        
        public $address;
        
        public $sum;
        
        public $bank;
        
        public $payedOn;
        
        public $payedAt;
        
        public $checkNum;
        
        public $comment;
        
        public $captcha;
        
        public function init() {
            if(!\Yii::$app->user->isGuest) {
                /**
                 * @var Customer $user
                 */
                $user = \Yii::$app->user->identity;
                if(empty($this->name)) {
                    $this->name = $user->username;
                }
                if(empty($this->address)) {
                    $this->address = $user->address;
                }
            }
            return parent::init();
        }
    
        public function attributeLabels()
        {
            return [
                'orderId'  => \Yii::t('app', '№ заказа'),
                'name'     => \Yii::t('app', 'ФИО'),
                'file'     => \Yii::t('app', 'Загрузить квитанцию'),
                'address'  => \Yii::t('app', 'Ваш адрес (город)'),
                'sum'      => \Yii::t('app', 'Сумма платежа'),
                'bank'     => \Yii::t('app', 'Банк'),
                'payedOn'  => \Yii::t('app', 'Дата платежа'),
                'payedAt'  => \Yii::t('app', 'Время платежа'),
                'checkNum' => \Yii::t('app', 'Номер чека'),
                'comment'  => \Yii::t('app', 'Комментарий'),
                'captcha'  => \Yii::t('app', 'Captcha'),
            ];
        }
        
        public function rules()
        {
            return [
                [
                    [
                        'orderId',
                    ],
                    'integer',
                ],
                [
                    [
                        'orderId',
                    ],
                    'exist',
                    'targetClass'     => OrderFrontend::className(),
                    'targetAttribute' => 'id',
                    'allowArray'      => false,
                ],
                [
                    [
                        'name',
                        'address',
                        'bank',
                        'checkNum',
                        'comment',
                    ],
                    'string',
                    'max' => 255,
                ],
                [
                    [
                        'name',
                        'sum',
                        'checkNum',
                    ],
                    'required',
                ],
                [
                    [
                        'file',
                    ],
                    'file',
                    'extensions' => [
                        'png',
                        'jpg',
                        'gif',
                        'bmp',
                    ],
                ],
                [
                    [
                        'sum',
                    ],
                    'number',
                ],
                [
                    [
                        'payedOn',
                        'payedAt',
                    ],
                    'safe',
                ],
                [
                    [
                        'captcha',
                    ],
                    'captcha',
                ],
            ];
        }
        
    }