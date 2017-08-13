<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveRecord;
    use common\models\User;
    use Yii;
    
    /**
     * This is the model class for table "order_label_history".
     *
     * @property integer $id
     * @property integer $label_id
     * @property integer $order_id
     * @property integer $user_id
     * @property integer $created_at
     * @property Order   $order
     * @property Label   $label
     * @property User    $user
     */
    class OrderLabelHistory extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'order_label_history';
        }
        
        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                [
                    'class'              => TimestampBehavior::className(),
                    'updatedAtAttribute' => false,
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
                        'label_id',
                        'order_id',
                        'user_id',
                        'created_at',
                    ],
                    'integer',
                ],
                [
                    [ 'order_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Order::className(),
                    'targetAttribute' => [ 'order_id' => 'id' ],
                ],
                [
                    [ 'label_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Label::className(),
                    'targetAttribute' => [ 'label_id' => 'id' ],
                ],
//                [
//                    [ 'user_id' ],
//                    'exist',
//                    'skipOnError'     => true,
//                    'targetClass'     => User::className(),
//                    'targetAttribute' => [ 'user_id' => 'id' ],
//                ],
            ];
        }
    
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'         => Yii::t('app', 'ID'),
                'label_id'   => Yii::t('app', 'Label ID'),
                'order_id'   => Yii::t('app', 'Order ID'),
                'user_id'    => Yii::t('app', 'User ID'),
                'created_at' => Yii::t('app', 'Created At'),
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
        public function getLabel()
        {
            return $this->hasOne(Label::className(), [ 'id' => 'label_id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getUser()
        {
            return $this->hasOne(User::className(), [ 'id' => 'user_id' ]);
        }
    }
