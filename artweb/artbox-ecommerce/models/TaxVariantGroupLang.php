<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use artweb\artbox\language\models\Language;
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "tax_variant_group_lang".
     *
     * @property integer         $tax_variant_group_id
     * @property integer         $language_id
     * @property string          $title
     * @property string          $alias
     * @property string          $description
     * @property Language        $language
     * @property TaxVariantGroup $taxGroup
     */
    class TaxVariantGroupLang extends ActiveRecord
    {
        
        public static function primaryKey()
        {
            return [
                'tax_variant_group_id',
                'language_id',
            ];
        }
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'tax_variant_group_lang';
        }
        
        public function behaviors()
        {
            return [
                'slug' => [
                    'class' => 'artweb\artbox\behaviors\Slug',
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
                        'tax_variant_group_id',
                        'language_id',
                    ],
                    'unique',
                    'targetAttribute' => [
                        'tax_variant_group_id',
                        'language_id',
                    ],
                    'message'         => 'The combination of Tax Variant Group ID and Language ID has already been taken.',
                ],
                [
                    [ 'language_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Language::className(),
                    'targetAttribute' => [ 'language_id' => 'id' ],
                ],
                [
                    [ 'tax_variant_group_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => TaxVariantGroup::className(),
                    'targetAttribute' => [ 'tax_variant_group_id' => 'id' ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'tax_variant_group_id' => Yii::t('app', 'Tax Variant Group ID'),
                'language_id'          => Yii::t('app', 'Language ID'),
                'title'                => Yii::t('app', 'Name'),
                'description'          => Yii::t('app', 'Description'),
                'alias'                => Yii::t('app', 'Alias'),
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
        public function getTaxVariantGroup()
        {
            return $this->hasOne(TaxVariantGroup::className(), [ 'id' => 'tax_variant_group_id' ]);
        }
    }
