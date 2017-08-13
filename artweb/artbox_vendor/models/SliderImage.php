<?php
    
    namespace artweb\artbox\models;
    
    use artweb\artbox\behaviors\SaveImgBehavior;
    use artweb\artbox\modules\language\behaviors\LanguageBehavior;
    use Yii;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;
    use yii\web\Request;
    
    /**
     * This is the model class for table "slider_image".
     *
     * @property integer           $id
     * @property integer           $slider_id
     * @property string            $image
     * @property string            $url
     * @property integer           $status
     * @property integer           $sort
     * @property Slider            $slider
     * * From language behavior *
     * @property SliderImageLang   $lang
     * @property SliderImageLang[] $langs
     * @property SliderImageLang   $objectLang
     * @property string            $ownerKey
     * @property string            $langKey
     * @property SliderImageLang[] $modelLangs
     * @property bool              $transactionStatus
     * @method string           getOwnerKey()
     * @method void             setOwnerKey( string $value )
     * @method string           getLangKey()
     * @method void             setLangKey( string $value )
     * @method ActiveQuery      getLangs()
     * @method ActiveQuery      getLang( integer $language_id )
     * @method SliderImageLang[]    generateLangs()
     * @method void             loadLangs( Request $request )
     * @method bool             linkLangs()
     * @method bool             saveLangs()
     * @method bool             getTransactionStatus()
     * * End language behavior *
     * * From SaveImgBehavior
     * @property string|null       $imageFile
     * @property string|null       $imageUrl
     * @method string|null getImageFile( int $field )
     * @method string|null getImageUrl( int $field )
     * * End SaveImgBehavior
     */
    class SliderImage extends ActiveRecord
    {
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'slider_image';
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [
                        'slider_id',
                        'status',
                        'sort',
                    ],
                    'integer',
                ],
                [
                    [
                        'url',
                    ],
                    'string',
                    'max' => 255,
                ],
                [
                    [ 'slider_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Slider::className(),
                    'targetAttribute' => [ 'slider_id' => 'id' ],
                ],
            ];
        }
        
        public function behaviors()
        {
            return [
                'language' => [
                    'class' => LanguageBehavior::className(),
                ],
                [
                    'class'  => SaveImgBehavior::className(),
                    'fields' => [
                        [
                            'name'      => 'image',
                            'directory' => 'slider',
                        ],
                    ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'        => Yii::t('app', 'slider_image_id'),
                'slider_id' => Yii::t('app', 'slider_id'),
                'image'     => Yii::t('app', 'image'),
                'url'       => Yii::t('app', 'url'),
                'status'    => Yii::t('app', 'status'),
                'sort'      => Yii::t('app', 'sort'),
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getSlider()
        {
            return $this->hasOne(Slider::className(), [ 'id' => 'slider_id' ]);
        }
    }
