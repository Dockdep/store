<?php
    
    namespace artweb\artbox\modules\catalog\models;
    
    use artweb\artbox\modules\language\models\Language;
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "product_unit_lang".
     *
     * @property integer     $product_unit_id
     * @property integer     $language_id
     * @property string      $title
     * @property string      $short
     * @property Language    $language
     * @property ProductUnit $productUnit
     */
    class ProductUnitLang extends ActiveRecord
    {
        
        public static function primaryKey()
        {
            return [
                'product_unit_id',
                'language_id',
            ];
        }
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'product_unit_lang';
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
                        'short',
                    ],
                    'string',
                    'max' => 255,
                ],
                [
                    [
                        'product_unit_id',
                        'language_id',
                    ],
                    'unique',
                    'targetAttribute' => [
                        'product_unit_id',
                        'language_id',
                    ],
                    'message'         => 'The combination of Product Unit ID and Language ID has already been taken.',
                ],
                [
                    [ 'language_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Language::className(),
                    'targetAttribute' => [ 'language_id' => 'id' ],
                ],
                [
                    [ 'product_unit_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => ProductUnit::className(),
                    'targetAttribute' => [ 'product_unit_id' => 'id' ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'product_unit_id' => Yii::t('app', 'Product Unit ID'),
                'language_id'     => Yii::t('app', 'Language ID'),
                'title'           => Yii::t('app', 'Name'),
                'short'           => Yii::t('app', 'Short'),
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
        public function getProductUnit()
        {
            return $this->hasOne(ProductUnit::className(), [ 'id' => 'product_unit_id' ]);
        }
    }
