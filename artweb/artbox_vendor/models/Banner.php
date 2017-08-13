<?php
    
    namespace artweb\artbox\models;
    
    use artweb\artbox\modules\language\behaviors\LanguageBehavior;
    use Yii;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;
    use yii\web\Request;
    
    /**
     * This is the model class for table "banner".
     *
     * @property integer      $id
     * @property string       $url
     * @property integer      $status
     * * From language behavior *
     * @property BannerLang   $lang
     * @property BannerLang[] $langs
     * @property BannerLang   $objectLang
     * @property string       $ownerKey
     * @property string       $langKey
     * @property BannerLang[] $modelLangs
     * @property bool         $transactionStatus
     * @method string           getOwnerKey()
     * @method void             setOwnerKey( string $value )
     * @method string           getLangKey()
     * @method void             setLangKey( string $value )
     * @method ActiveQuery      getLangs()
     * @method ActiveQuery      getLang( integer $language_id )
     * @method BannerLang[]    generateLangs()
     * @method void             loadLangs( Request $request )
     * @method bool             linkLangs()
     * @method bool             saveLangs()
     * @method bool             getTransactionStatus()
     * * End language behavior *
     */
    class Banner extends ActiveRecord
    {
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'banner';
        }
        
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
                    [ 'status' ],
                    'integer',
                ],
                [
                    [ 'url' ],
                    'string',
                    'max' => 255,
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'     => Yii::t('app', 'id'),
                'url'    => Yii::t('app', 'url'),
                'status' => Yii::t('app', 'status'),
            ];
        }
    }
