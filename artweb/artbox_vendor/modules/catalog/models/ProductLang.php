<?php
    
    namespace artweb\artbox\modules\catalog\models;
    
    use artweb\artbox\modules\language\models\Language;
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "product_lang".
     * @property integer  $product_id
     * @property integer  $language_id
     * @property string   $title
     * @property string   $description
     * @property string   $alias
     * @property Language $language
     * @property Product  $product
     */
    class ProductLang extends ActiveRecord
    {
        
        public static function primaryKey()
        {
            return [
                'product_id',
                'language_id',
            ];
        }
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'product_lang';
        }
        
        public function behaviors()
        {
            return [
                'slug' => [
                    'class'         => 'artweb\artbox\behaviors\Slug',
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
                    [ 'title' ],
                    'required',
                ],
                [
                    [ 'description' ],
                    'string',
                ],
                [
                    [
                        'title',
                        'alias',
                    ],
                    'string',
                    'max' => 255,
                ],
                [
                    [
                        'product_id',
                        'language_id',
                    ],
                    'unique',
                    'targetAttribute' => [
                        'product_id',
                        'language_id',
                    ],
                    'message'         => 'The combination of Product ID and Language ID has already been taken.',
                ],
                [
                    [ 'language_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Language::className(),
                    'targetAttribute' => [ 'language_id' => 'id' ],
                ],
                [
                    [ 'product_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Product::className(),
                    'targetAttribute' => [ 'product_id' => 'id' ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'product_id'  => Yii::t('app', 'Product ID'),
                'language_id' => Yii::t('app', 'Language ID'),
                'title'        => Yii::t('app', 'Name'),
                'description' => Yii::t('app', 'Description'),
                'alias'       => Yii::t('app', 'Alias'),
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
        public function getProduct()
        {
            return $this->hasOne(Product::className(), [ 'id' => 'product_id' ]);
        }
    }
