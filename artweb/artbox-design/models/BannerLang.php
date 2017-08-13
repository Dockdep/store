<?php
    
    namespace artweb\artbox\design\models;
    
    use artweb\artbox\behaviors\SaveImgBehavior;
    use artweb\artbox\language\models\Language;
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "banner_lang".
     *
     * @property integer     $banner_id
     * @property integer     $language_id
     * @property string      $alt
     * @property string      $title
     * @property string      $image
     * @property Banner      $banner
     * @property Language    $language
     * * From SaveImgBehavior
     * @property string|null $imageFile
     * @property string|null $imageUrl
     * @method string|null getImageFile( int $field )
     * @method string|null getImageUrl( int $field )
     * * End SaveImgBehavior
     */
    class BannerLang extends ActiveRecord
    {
        
        public static function primaryKey()
        {
            return [
                'banner_id',
                'language_id',
            ];
        }
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'banner_lang';
        }
        
        public function behaviors()
        {
            return [
                [
                    'class'      => SaveImgBehavior::className(),
                    'isLanguage' => true,
                    'fields'     => [
                        [
                            'name'      => 'image',
                            'directory' => 'banner',
                        ],
                    ],
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
                    [
                        'alt',
                        'title',
                        'image',
                    ],
                    'string',
                    'max' => 255,
                ],
                [
                    [
                        'banner_id',
                        'language_id',
                    ],
                    'unique',
                    'targetAttribute' => [
                        'banner_id',
                        'language_id',
                    ],
                    'message'         => 'The combination of Banner ID and Language ID has already been taken.',
                ],
                [
                    [ 'banner_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Banner::className(),
                    'targetAttribute' => [ 'banner_id' => 'id' ],
                ],
                [
                    [ 'language_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Language::className(),
                    'targetAttribute' => [ 'language_id' => 'id' ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'banner_id'   => Yii::t('app', 'banner_id'),
                'language_id' => Yii::t('app', 'language_id'),
                'alt'         => Yii::t('app', 'alt'),
                'title'       => Yii::t('app', 'title'),
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getBanner()
        {
            return $this->hasOne(Banner::className(), [ 'id' => 'banner_id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getLanguage()
        {
            return $this->hasOne(Language::className(), [ 'id' => 'language_id' ]);
        }
    }
