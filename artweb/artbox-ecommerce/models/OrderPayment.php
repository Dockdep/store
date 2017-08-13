<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use artweb\artbox\language\behaviors\LanguageBehavior;
    use yii\db\ActiveQuery;
    use yii\web\Request;
    
    /**
     * This is the model class for table "order_payment".
     *
     * @property integer            $id
     * @property integer            $status
     * @property string             $short
     * @property OrderPaymentLang[] $orderPaymentLangs
     * * From language behavior *
     * @property orderPaymentLang   $lang
     * @property orderPaymentLang[] $langs
     * @property orderPaymentLang   $objectLang
     * @property string             $ownerKey
     * @property string             $langKey
     * @property orderPaymentLang[] $modelLangs
     * @property bool               $transactionStatus
     * @method string           getOwnerKey()
     * @method void             setOwnerKey( string $value )
     * @method string           getLangKey()
     * @method void             setLangKey( string $value )
     * @method ActiveQuery      getLangs()
     * @method ActiveQuery      getLang( integer $language_id )
     * @method OrderPaymentLang[]    generateLangs()
     * @method void             loadLangs( Request $request )
     * @method bool             linkLangs()
     * @method bool             saveLangs()
     * @method bool             getTransactionStatus()
     * * End language behavior
     */
    class OrderPayment extends \yii\db\ActiveRecord
    {
        
        const ACTIVE = 1;
        const INACTIVE = 2;
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'order_payment';
        }
        
        public function behaviors()
        {
            return [
                'language' => [
                    'class'      => LanguageBehavior::className(),
                    'objectLang' => OrderPaymentLang::className(),
                    'ownerKey'   => 'id',
                    'langKey'    => 'order_payment_id',
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
                    [ 'short' ],
                    'string',
                ],
                [
                    [ 'status' ],
                    'integer',
                ],
                [
                    [ 'status' ],
                    'default',
                    'value' => 1,
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'     => \Yii::t('app', 'ID'),
                'status' => \Yii::t('app', 'Статус'),
                'short'  => \Yii::t('app', 'Название'),
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getOrderPaymentLangs()
        {
            return $this->hasMany(OrderPaymentLang::className(), [ 'order_payment_id' => 'id' ]);
        }
    }
