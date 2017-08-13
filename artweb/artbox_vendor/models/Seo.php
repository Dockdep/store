<?php
    
    namespace artweb\artbox\models;
    
    use artweb\artbox\modules\language\behaviors\LanguageBehavior;
    use Yii;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;
    use yii\web\Request;
    
    /**
     * This is the model class for table "seo".
     *
     * @property integer   $id
     * @property string    $url
     * * From language behavior *
     * @property SeoLang   $lang
     * @property SeoLang[] $langs
     * @property SeoLang   $objectLang
     * @property string    $ownerKey
     * @property string    $langKey
     * @property SeoLang[] $modelLangs
     * @property bool      $transactionStatus
     * @method string           getOwnerKey()
     * @method void             setOwnerKey( string $value )
     * @method string           getLangKey()
     * @method void             setLangKey( string $value )
     * @method ActiveQuery      getLangs()
     * @method ActiveQuery      getLang( integer $language_id )
     * @method SeoLang[]    generateLangs()
     * @method void             loadLangs( Request $request )
     * @method bool             linkLangs()
     * @method bool             saveLangs()
     * @method bool             getTransactionStatus()
     * * End language behavior *
     */
    class Seo extends ActiveRecord
    {
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'seo';
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
                    [ 'url' ],
                    'required',
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
                'id'  => Yii::t('app', 'seo_id'),
                'url' => Yii::t('app', 'url'),
            ];
        }
    }
