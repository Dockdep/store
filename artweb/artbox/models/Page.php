<?php
    
    namespace artweb\artbox\models;
    
    use artweb\artbox\language\behaviors\LanguageBehavior;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;
    use yii\web\Request;
    use Yii;
    
    /**
     * This is the model class for table "page".
     *
     * @property integer    $id
     * @property bool       $in_menu
     * * From language behavior *
     * @property PageLang   $lang
     * @property PageLang[] $langs
     * @property PageLang   $objectLang
     * @property string     $ownerKey
     * @property string     $langKey
     * @property PageLang[] $modelLangs
     * @property bool       $transactionStatus
     * @method string           getOwnerKey()
     * @method void             setOwnerKey( string $value )
     * @method string           getLangKey()
     * @method void             setLangKey( string $value )
     * @method ActiveQuery      getLangs()
     * @method ActiveQuery      getLang( integer $language_id )
     * @method PageLang[]    generateLangs()
     * @method void             loadLangs( Request $request )
     * @method bool             linkLangs()
     * @method bool             saveLangs()
     * @method bool             getTransactionStatus()
     * * End language behavior *
     */
    class Page extends ActiveRecord
    {
        
        public $title;
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'page';
        }
        
        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                'language' => [
                    'class' => LanguageBehavior::className(),
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
                    [ 'title' ],
                    'safe',
                ],
                [
                    [
                        'in_menu',
                    ],
                    'boolean',
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'      => Yii::t('app', 'id'),
                'in_menu' => Yii::t('app', 'in_menu'),
            ];
        }
    }
