<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use artweb\artbox\language\models\Language;
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "order_label_lang".
     *
     * @property integer  $order_label_id
     * @property integer  $language_id
     * @property string   $title
     * @property Language $language
     * @property Label    $label
     */
    class OrderLabelLang extends ActiveRecord
    {
        
        public static function primaryKey()
        {
            return [
                'order_label_id',
                'language_id',
            ];
        }
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'order_label_lang';
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
                        'order_label_id',
                        'language_id',
                    ],
                    'unique',
                    'targetAttribute' => [
                        'order_label_id',
                        'language_id',
                    ],
                    'message'         => 'The combination of order Label ID and Language ID has already been taken.',
                ],
                [
                    [ 'language_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Language::className(),
                    'targetAttribute' => [ 'language_id' => 'id' ],
                ],
                [
                    [ 'order_label_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Label::className(),
                    'targetAttribute' => [ 'order_label_id' => 'id' ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'order_label_id' => Yii::t('app', 'order Label ID'),
                'language_id'    => Yii::t('app', 'Language ID'),
                'title'          => Yii::t('app', 'Name'),
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
        public function getLabel()
        {
            return $this->hasOne(Label::className(), [ 'id' => 'order_label_id' ]);
        }
    }
