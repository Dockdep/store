<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use common\models\User;
    use Yii;
    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "order_log".
     *
     * @property integer $id
     * @property integer $order_id
     * @property integer $created_at
     * @property integer $user_id
     * @property string  $data
     * @property Order   $order
     * @property User    $user
     */
    class OrderLog extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'order_log';
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
                        'order_id',
                        'created_at',
                        'user_id',
                    ],
                    'integer',
                ],
                [
                    [ 'data' ],
                    'string',
                ],
                [
                    [ 'order_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Order::className(),
                    'targetAttribute' => [ 'order_id' => 'id' ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'         => Yii::t('app', 'ID'),
                'order_id'   => Yii::t('app', 'Order ID'),
                'created_at' => Yii::t('app', 'Created At'),
                'user_id'    => Yii::t('app', 'User ID'),
                'data'       => Yii::t('app', 'Data'),
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
        public function getUser()
        {
            return $this->hasOne(User::className(), [ 'id' => 'user_id' ]);
        }
    }
