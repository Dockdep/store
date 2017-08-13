<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use artweb\artbox\language\behaviors\LanguageBehavior;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;
    use yii\db\Query;
    use yii\helpers\VarDumper;
    use yii\web\Request;
    
    /**
     * Class Label
     *
     * @property int              $id
     * @property string           $label
     * * From language behavior *
     * @property orderLabelLang   $lang
     * @property orderLabelLang[] $langs
     * @property orderLabelLang   $objectLang
     * @property string           $ownerKey
     * @property string           $langKey
     * @property orderLabelLang[] $modelLangs
     * @property bool             $transactionStatus
     * @method string           getOwnerKey()
     * @method void             setOwnerKey( string $value )
     * @method string           getLangKey()
     * @method void             setLangKey( string $value )
     * @method ActiveQuery      getLangs()
     * @method ActiveQuery      getLang( integer $language_id )
     * @method OrderLabelLang[]    generateLangs()
     * @method void             loadLangs( Request $request )
     * @method bool             linkLangs()
     * @method bool             saveLangs()
     * @method bool             getTransactionStatus()
     * * End language behavior
     */
    class Label extends ActiveRecord
    {
        
        public function rules()
        {
            return [
                [
                    [ 'label' ],
                    'string',
                ],
            ];
        }
        
        public static function tableName()
        {
            return 'order_label';
        }
        
        public function behaviors()
        {
            return [
                'language' => [
                    'class'      => LanguageBehavior::className(),
                    'objectLang' => OrderLabelLang::className(),
                    'ownerKey'   => 'id',
                    'langKey'    => 'order_label_id',
                ],
            ];
        }
        
        public function getStatistics(array $date = [], array $manager = [])
        {
            $query = ( new Query() )->select(
                [
                    'sum'      => 'SUM(total)',
                    'count'    => 'COUNT(*)',
                    'unique'   => ( new Query() )->select('COUNT(*)')
                                                 ->from('order')
                                                 ->leftJoin(
                                                     'order_product',
                                                     '"order"."id"="order_product"."order_id"'
                                                 )
                                                 ->where([ 'order.label' => $this->id ])
                                                 ->andFilterWhere(
                                                     $date
                                                 )
                                                 ->andFilterWhere($manager),
                    'products' => ( new Query() )->select('SUM(count)')
                                                 ->from('order')
                                                 ->leftJoin(
                                                     'order_product',
                                                     '"order"."id"="order_product"."order_id"'
                                                 )
                                                 ->where([ 'order.label' => $this->id ])
                                                 ->andFilterWhere(
                                                     $date
                                                 )
                                                 ->andFilterWhere($manager),
                ]
            )
                                    ->from('order')
                                    ->where([ 'label' => $this->id ])
                                    ->andFilterWhere(
                                        $date
                                    )
                                    ->andFilterWhere($manager);
            
            return $query->one();
        }
    }
