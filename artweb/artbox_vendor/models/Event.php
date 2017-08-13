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
     * This is the model class for table "event".
     *
     * @property integer     $id
     * @property string      $image
     * @property integer     $created_at
     * @property integer     $updated_at
     * @property integer     $end_at
     * * From language behavior *
     * @property EventLang   $lang
     * @property EventLang[] $langs
     * @property EventLang   $objectLang
     * @property string      $ownerKey
     * @property string      $langKey
     * @property EventLang[] $modelLangs
     * @property bool        $transactionStatus
     * @method string           getOwnerKey()
     * @method void             setOwnerKey( string $value )
     * @method string           getLangKey()
     * @method void             setLangKey( string $value )
     * @method ActiveQuery      getLangs()
     * @method ActiveQuery      getLang( integer $language_id )
     * @method EventLang[]    generateLangs()
     * @method void             loadLangs( Request $request )
     * @method bool             linkLangs()
     * @method bool             saveLangs()
     * @method bool             getTransactionStatus()
     * * End language behavior *
     * * From SaveImgBehavior
     * @property string|null $imageFile
     * @property string|null $imageUrl
     * @method string|null getImageFile( int $field )
     * @method string|null getImageUrl( int $field )
     * * End SaveImgBehavior
     */
    class Event extends ActiveRecord
    {
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'event';
        }
        
        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                TimestampBehavior::className(),
                'language' => [
                    'class' => LanguageBehavior::className(),
                ],
                [
                    'class'  => SaveImgBehavior::className(),
                    'fields' => [
                        [
                            'name'      => 'image',
                            'directory' => 'event',
                        ],
                    ],
                ],
            ];
        }
        
        public function beforeSave($insert)
        {
            if (parent::beforeSave($insert)) {
                $this->end_at = strtotime($this->end_at);
                return true;
            } else {
                return false;
            }
        }
        
        public function afterFind()
        {
            $this->end_at = date("Y-m-d", $this->end_at);
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
                [
                    [ 'end_at' ],
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
                'id'         => Yii::t('app', 'id'),
                'image'      => Yii::t('app', 'image'),
                'created_at' => Yii::t('app', 'created_at'),
                'updated_at' => Yii::t('app', 'updated_at'),
                'end_at'     => Yii::t('app', 'end_at'),
            ];
        }
    }
