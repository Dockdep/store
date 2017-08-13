<?php
    
    namespace artweb\artbox\models;
    
    use artweb\artbox\modules\language\behaviors\LanguageBehavior;
    use Yii;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;
    use yii\web\Request;
    
    /**
     * This is the model class for table "seo_dynamic".
     *
     * @property integer          $id
     * @property integer          $seo_category_id
     * @property string           $action
     * @property string           $fields
     * @property integer          $status
     * @property string           $param
     * * From language behavior *
     * @property SeoDynamicLang   $lang
     * @property SeoDynamicLang[] $langs
     * @property SeoDynamicLang   $objectLang
     * @property string           $ownerKey
     * @property string           $langKey
     * @property SeoDynamicLang[] $modelLangs
     * @property bool             $transactionStatus
     * @method string           getOwnerKey()
     * @method void             setOwnerKey( string $value )
     * @method string           getLangKey()
     * @method void             setLangKey( string $value )
     * @method ActiveQuery      getLangs()
     * @method ActiveQuery      getLang( integer $language_id )
     * @method SeoDynamicLang[]    generateLangs()
     * @method void             loadLangs( Request $request )
     * @method bool             linkLangs()
     * @method bool             saveLangs()
     * @method bool             getTransactionStatus()
     * * End language behavior *
     * @property SeoCategory      $seoCategory
     */
    class SeoDynamic extends ActiveRecord
    {
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'seo_dynamic';
        }
        
        public function behaviors()
        {
            return [
                'language' => [
                    'class' => LanguageBehavior::className(),
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
                        'seo_category_id',
                        'status',
                    ],
                    'integer',
                ],
                [
                    [
                        'action',
                    ],
                    'string',
                    'max' => 200,
                ],
                [
                    [
                        'fields',
                        'param',
                    ],
                    'string',
                    'max' => 255,
                ],
                [
                    [ 'seo_category_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => SeoCategory::className(),
                    'targetAttribute' => [ 'seo_category_id' => 'id' ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'              => Yii::t('app', 'seo_dynamic_id'),
                'seo_category_id' => Yii::t('app', 'seo_category_id'),
                'action'          => Yii::t('app', 'action'),
                'fields'          => Yii::t('app', 'fields'),
                'status'          => Yii::t('app', 'status'),
                'param'           => Yii::t('app', 'param'),
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getSeoCategory()
        {
            return $this->hasOne(SeoCategory::className(), [ 'id' => 'seo_category_id' ]);
        }
    }
