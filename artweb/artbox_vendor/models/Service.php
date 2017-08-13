<?php
    
    namespace artweb\artbox\models;
    
    use artweb\artbox\behaviors\SaveImgBehavior;
    use artweb\artbox\modules\language\behaviors\LanguageBehavior;
    use Yii;
    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;
    use yii\web\Request;
    
    /**
     * This is the model class for table "service".
     *
     * @property integer       $id
     * @property string        $image
     * @property integer       $created_at
     * @property integer       $updated_at
     * * From language behavior *
     * @property ServiceLang   $lang
     * @property ServiceLang[] $langs
     * @property ServiceLang   $objectLang
     * @property string        $ownerKey
     * @property string        $langKey
     * @property ServiceLang[] $modelLangs
     * @property bool          $transactionStatus
     * @method string           getOwnerKey()
     * @method void             setOwnerKey( string $value )
     * @method string           getLangKey()
     * @method void             setLangKey( string $value )
     * @method ActiveQuery      getLangs()
     * @method ActiveQuery      getLang( integer $language_id )
     * @method ServiceLang[]    generateLangs()
     * @method void             loadLangs( Request $request )
     * @method bool             linkLangs()
     * @method bool             saveLangs()
     * @method bool             getTransactionStatus()
     * * End language behavior *
     * * From SaveImgBehavior
     * @property string|null   $imageFile
     * @property string|null   $imageUrl
     * @method string|null getImageFile( int $field )
     * @method string|null getImageUrl( int $field )
     * * End SaveImgBehavior
     */
    class Service extends ActiveRecord
    {
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'service';
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [
                        'created_at',
                        'updated_at',
                    ],
                    'integer',
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                [
                    'class'  => SaveImgBehavior::className(),
                    'fields' => [
                        [
                            'name'      => 'image',
                            'directory' => 'service',
                        ],
                    ],
                ],
                TimestampBehavior::className(),
                'language' => [
                    'class' => LanguageBehavior::className(),
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'         => Yii::t('app', 'service_id'),
                'image'      => Yii::t('app', 'image'),
                'created_at' => Yii::t('app', 'created_at'),
                'updated_at' => Yii::t('app', 'updated_at'),
            ];
        }
    }
