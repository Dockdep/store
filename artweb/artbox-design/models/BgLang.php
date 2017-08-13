<?php
    
    namespace artweb\artbox\design\models;
    
    use artweb\artbox\language\models\Language;
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "bg_lang".
     * @property integer  $bg_id
     * @property integer  $language_id
     * @property string   $title
     * @property Bg       $bg
     * @property Language $language
     */
    class BgLang extends ActiveRecord
    {
        
        public static function primaryKey()
        {
            return [
                'bg_id',
                'language_id',
            ];
        }
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'bg_lang';
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [ 'title' ],
                    'required',
                ],
                [
                    [ 'title' ],
                    'string',
                    'max' => 255,
                ],
                [
                    [
                        'bg_id',
                        'language_id',
                    ],
                    'unique',
                    'targetAttribute' => [
                        'bg_id',
                        'language_id',
                    ],
                    'message'         => 'The combination of Bg ID and Language ID has already been taken.',
                ],
                [
                    [ 'bg_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Bg::className(),
                    'targetAttribute' => [ 'bg_id' => 'id' ],
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
                'bg_id'       => Yii::t('app', 'bg_id'),
                'language_id' => Yii::t('app', 'language_id'),
                'title'       => Yii::t('app', 'title'),
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getBg()
        {
            return $this->hasOne(Bg::className(), [ 'id' => 'bg_id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getLanguage()
        {
            return $this->hasOne(Language::className(), [ 'id' => 'language_id' ]);
        }
    }
