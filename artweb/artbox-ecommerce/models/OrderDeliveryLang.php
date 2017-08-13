<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use artweb\artbox\language\models\Language;
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "order_delivery_lang".
     *
     * @property integer  $order_delivery_id
     * @property integer  $language_id
     * @property string   $title
     * @property string   $text
     * @property Language $language
     * @property Delivery $delivery
     */
    class OrderDeliveryLang extends ActiveRecord
    {
        
        /**
         * @inheritdoc
         */
        public static function primaryKey()
        {
            return [
                'order_delivery_id',
                'language_id',
            ];
        }
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'order_delivery_lang';
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [
                        'title',
                    ],
                    'required',
                ],
                [
                    [ 'text' ],
                    'string',
                ],
                [
                    [ 'title' ],
                    'string',
                    'max' => 255,
                ],
                [
                    [
                        'order_delivery_id',
                        'language_id',
                    ],
                    'unique',
                    'targetAttribute' => [
                        'order_delivery_id',
                        'language_id',
                    ],
                    'message'         => 'The combination of order Delivery ID and Language ID has already been taken.',
                ],
                [
                    [ 'language_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Language::className(),
                    'targetAttribute' => [ 'language_id' => 'id' ],
                ],
                [
                    [ 'order_delivery_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Delivery::className(),
                    'targetAttribute' => [ 'order_delivery_id' => 'id' ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'order_delivery_id' => Yii::t('app', 'order_delivery_id'),
                'language_id'       => Yii::t('app', 'language_id'),
                'title'             => Yii::t('app', 'title'),
                'text'              => Yii::t('app', 'text'),
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getLanguage()
        {
            return $this->hasOne(Language::className(), [ 'id' => 'language_id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getDelivery()
        {
            return $this->hasOne(Delivery::className(), [ 'id' => 'order_delivery_id' ]);
        }
    }
