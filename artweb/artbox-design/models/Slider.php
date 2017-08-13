<?php
    
    namespace artweb\artbox\design\models;
    
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "slider".
     *
     * @property integer       $id
     * @property integer       $speed
     * @property integer       $duration
     * @property string        $title
     * @property integer       $status
     * @property integer       $width
     * @property integer       $height
     * @property SliderImage[] $sliderImages
     */
    class Slider extends ActiveRecord
    {
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'slider';
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [
                        'speed',
                        'duration',
                        'status',
                        'width',
                        'height',
                    ],
                    'integer',
                ],
                [
                    [ 'title' ],
                    'string',
                    'max' => 200,
                ],
                [
                    [
                        'width',
                        'height',
                        'title',
                    ],
                    'required',
                ],
                [
                    'title',
                    'unique',
                    'targetClass' => '\artweb\artbox\design\models\Slider',
                    'message'     => Yii::t(
                        'app',
                        'message',
                        [
                            'field' => 'Title',
                        ]
                    ),
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'       => Yii::t('app', 'slider_id'),
                'speed'    => Yii::t('app', 'speed'),
                'duration' => Yii::t('app', 'duration'),
                'title'    => Yii::t('app', 'title'),
                'status'   => Yii::t('app', 'status'),
                'width'    => Yii::t('app', 'width'),
                'height'   => Yii::t('app', 'height'),
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getSliderImages()
        {
            return $this->hasMany(SliderImage::className(), [ 'slider_id' => 'id' ])
                        ->where([ SliderImage::tableName() . '.status' => 1 ]);
        }
        
    }
