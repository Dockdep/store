<?php
    
    namespace artweb\artbox\design\models;
    
    use artweb\artbox\behaviors\SaveImgBehavior;
    use artweb\artbox\language\behaviors\LanguageBehavior;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;
    use yii\web\Request;
    use Yii;
    
    /**
     * Class Bg
     *
     * @property int         $id
     * @property string      $url
     * @property string      $image
     *       * From language behavior *
     * @property BgLang      $lang
     * @property BgLang[]    $langs
     * @property BgLang      $objectLang
     * @property string      $ownerKey
     * @property string      $langKey
     * @property BgLang[]    $modelLangs
     * @property bool        $transactionStatus
     * @method string           getOwnerKey()
     * @method void             setOwnerKey( string $value )
     * @method string           getLangKey()
     * @method void             setLangKey( string $value )
     * @method ActiveQuery      getLangs()
     * @method ActiveQuery      getLang( integer $language_id )
     * @method BgLang[]    generateLangs()
     * @method void             loadLangs( Request $request )
     * @method bool             linkLangs()
     * @method bool             saveLangs()
     * @method bool             getTransactionStatus()
     *       * End language behavior *
     *       * From SaveImgBehavior
     * @property string|null $imageFile
     * @property string|null $imageUrl
     * @method string|null getImageFile( int $field )
     * @method string|null getImageUrl( int $field )
     *       * End SaveImgBehavior
     */
    class Bg extends ActiveRecord
    {
        
        public static function tableName()
        {
            return 'bg';
        }
        
        public function behaviors()
        {
            return [
                [
                    'class'  => SaveImgBehavior::className(),
                    'fields' => [
                        [
                            'name'      => 'image',
                            'directory' => 'bg',
                        ],
                    ],
                ],
                'language' => [
                    'class' => LanguageBehavior::className(),
                ],
            ];
        }
        
        public function rules()
        {
            return [
                [
                    [ 'url' ],
                    'string',
                ],
            ];
        }
        
        public function attributeLabels()
        {
            return [
                'image' => Yii::t('app', 'image'),
                'url'   => Yii::t('app', 'url'),
            ];
        }
    }
