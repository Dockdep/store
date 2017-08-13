<?php
    
    namespace artweb\artbox\modules\catalog\models;
    
    use artweb\artbox\modules\language\models\Language;
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "tax_group_lang".
     *
     * @property integer  $tax_group_id
     * @property integer  $language_id
     * @property string   $title
     * @property string   $alias
     * @property string   $description
     * @property Language $language
     * @property TaxGroup $taxGroup
     */
    class TaxGroupLang extends ActiveRecord
    {
        
        public static function primaryKey()
        {
            return [
                'tax_group_id',
                'language_id',
            ];
        }
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'tax_group_lang';
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
                        'tax_group_id',
                        'language_id',
                    ],
                    'unique',
                    'targetAttribute' => [
                        'tax_group_id',
                        'language_id',
                    ],
                    'message'         => 'The combination of Tax Group ID and Language ID has already been taken.',
                ],
                [
                    [ 'language_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Language::className(),
                    'targetAttribute' => [ 'language_id' => 'id' ],
                ],
                [
                    [ 'tax_group_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => TaxGroup::className(),
                    'targetAttribute' => [ 'tax_group_id' => 'id' ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'tax_group_id' => Yii::t('app', 'Tax Group ID'),
                'language_id'  => Yii::t('app', 'Language ID'),
                'title'        => Yii::t('app', 'Name'),
                'description'  => Yii::t('app', 'Description'),
                'alias'        => Yii::t('app', 'Alias'),
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
        public function getTaxGroup()
        {
            return $this->hasOne(TaxGroup::className(), [ 'id' => 'tax_group_id' ]);
        }
    }
