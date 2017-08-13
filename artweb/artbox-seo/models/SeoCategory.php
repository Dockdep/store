<?php
    
    namespace artweb\artbox\seo\models;
    
    use artweb\artbox\language\behaviors\LanguageBehavior;
    use Yii;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;
    use yii\web\Request;
    
    /**
     * This is the model class for table "seo_category".
     *
     * @property integer           $id
     * @property string            $controller
     * @property integer           $status
     * * From language behavior *
     * @property SeoCategoryLang   $lang
     * @property SeoCategoryLang[] $langs
     * @property SeoCategoryLang   $objectLang
     * @property string            $ownerKey
     * @property string            $langKey
     * @property SeoCategoryLang[] $modelLangs
     * @property bool              $transactionStatus
     * @method string           getOwnerKey()
     * @method void             setOwnerKey( string $value )
     * @method string           getLangKey()
     * @method void             setLangKey( string $value )
     * @method ActiveQuery      getLangs()
     * @method ActiveQuery      getLang( integer $language_id )
     * @method SeoCategoryLang[]    generateLangs()
     * @method void             loadLangs( Request $request )
     * @method bool             linkLangs()
     * @method bool             saveLangs()
     * @method bool             getTransactionStatus()
     * * End language behavior *
     * @property SeoDynamic[]      $seoDynamics
     */
    class SeoCategory extends ActiveRecord
    {
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'seo_category';
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
                    [ 'status' ],
                    'integer',
                ],
                [
                    [ 'controller' ],
                    'string',
                    'max' => 100,
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'         => Yii::t('app', 'seo_category_id'),
                'controller' => Yii::t('app', 'controller'),
                'status'     => Yii::t('app', 'status'),
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getSeoDynamics()
        {
            return $this->hasMany(SeoDynamic::className(), [ 'seo_category_id' => 'id' ]);
        }
    }
