<?php
    
    namespace artweb\artbox\modules\catalog\models;
    
    use artweb\artbox\modules\language\models\Language;
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "brand_lang".
     *
     * @property integer  $stock_id
     * @property integer  $language_id
     * @property string   $title
     * @property Stock    $stock
     * @property Language $language
     */
    class StockLang extends ActiveRecord
    {
        
        public static function primaryKey()
        {
            return [
                'stock_id',
                'language_id',
            ];
        }
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'stock_lang';
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
                    [
                        'title',
                    ],
                    'string',
                    'max' => 255,
                ],
                [
                    [
                        'stock_id',
                        'language_id',
                    ],
                    'unique',
                    'targetAttribute' => [
                        'stock_id',
                        'language_id',
                    ],
                    'message'         => 'The combination of Stock ID and Language ID has already been taken.',
                ],
                [
                    [ 'stock_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Stock::className(),
                    'targetAttribute' => [ 'stock_id' => 'id' ],
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
                'stock_id'    => Yii::t('app', 'Stock ID'),
                'language_id' => Yii::t('app', 'Language ID'),
                'title'       => Yii::t('app', 'Name'),
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getStock()
        {
            return $this->hasOne(Stock::className(), [ 'id' => 'stock_id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getLanguage()
        {
            return $this->hasOne(Language::className(), [ 'id' => 'language_id' ]);
        }
    }
