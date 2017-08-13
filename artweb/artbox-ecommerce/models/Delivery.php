<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use artweb\artbox\language\behaviors\LanguageBehavior;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;
    use yii\web\Request;
    
    /**
     * Class Delivery
     *
     * @property int                 $id
     * @property int                 $parent_id
     * @property int                 $value
     * @property int                 $sort
     * @property Delivery|null       $parent
     * @property Delivery[]          $children
     * * From language behavior *
     * @property OrderDeliveryLang   $lang
     * @property OrderDeliveryLang[] $langs
     * @property OrderDeliveryLang   $objectLang
     * @property string              $ownerKey
     * @property string              $langKey
     * @property OrderDeliveryLang[] $modelLangs
     * @property bool                $transactionStatus
     * @method string           getOwnerKey()
     * @method void             setOwnerKey( string $value )
     * @method string           getLangKey()
     * @method void             setLangKey( string $value )
     * @method ActiveQuery      getLangs()
     * @method ActiveQuery      getLang( integer $language_id )
     * @method OrderDeliveryLang[]    generateLangs()
     * @method void             loadLangs( Request $request )
     * @method bool             linkLangs()
     * @method bool             saveLangs()
     * @method bool             getTransactionStatus()
     * * End language behavior *
     */
    class Delivery extends ActiveRecord
    {
        
        public function behaviors()
        {
            return [
                'language' => [
                    'class'      => LanguageBehavior::className(),
                    'objectLang' => OrderDeliveryLang::className(),
                    'ownerKey'   => 'id',
                    'langKey'    => 'order_delivery_id',
                ],
            ];
        }
        
        public static function tableName()
        {
            return 'order_delivery';
        }
        
        public function rules()
        {
            return [
                [
                    [
                        'value',
                        'parent_id',
                        'sort',
                    ],
                    'integer',
                ],
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getParent()
        {
            return $this->hasOne(self::className(), [ 'id' => 'parent_id' ]);
        }
        
        public function getChildren()
        {
            return $this->hasMany(self::className(), [ 'parent_id' => 'id' ]);
        }
    }
    