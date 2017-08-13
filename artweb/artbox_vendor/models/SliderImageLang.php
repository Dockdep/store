<?php
    
    namespace artweb\artbox\models;
    
    use artweb\artbox\modules\language\models\Language;
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "slider_image_lang".
     *
     * @property integer     $slider_image_id
     * @property integer     $language_id
     * @property string      $title
     * @property string      $alt
     * @property Language    $language
     * @property SliderImage $sliderImage
     */
    class SliderImageLang extends ActiveRecord
    {
        
        public static function primaryKey()
        {
            return [
                'slider_image_id',
                'language_id',
            ];
        }
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'slider_image_lang';
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [
                        'title',
                        'alt',
                    ],
                    'string',
                    'max' => 255,
                ],
                [
                    [
                        'slider_image_id',
                        'language_id',
                    ],
                    'unique',
                    'targetAttribute' => [
                        'slider_image_id',
                        'language_id',
                    ],
                    'message'         => 'The combination of Slider Image ID and Language ID has already been taken.',
                ],
                [
                    [ 'language_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Language::className(),
                    'targetAttribute' => [ 'language_id' => 'id' ],
                ],
                [
                    [ 'slider_image_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => SliderImage::className(),
                    'targetAttribute' => [ 'slider_image_id' => 'id' ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'slider_image_id' => Yii::t('app', 'slider_image_id'),
                'language_id'     => Yii::t('app', 'language_id'),
                'title'           => Yii::t('app', 'title'),
                'alt'             => Yii::t('app', 'alt'),
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getLanguage()
        {
            return $this->hasOne(Language::className(), [ 'id' => 'language_id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getSliderImage()
        {
            return $this->hasOne(SliderImage::className(), [ 'id' => 'slider_image_id' ]);
        }
    }
