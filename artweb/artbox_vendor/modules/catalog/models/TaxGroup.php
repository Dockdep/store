<?php
    
    namespace artweb\artbox\modules\catalog\models;
    
    use artweb\artbox\modules\language\behaviors\LanguageBehavior;
    use artweb\artbox\modules\language\models\Language;
    use artweb\artbox\modules\catalog\models\Category;
    use yii\base\InvalidValueException;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;
    use yii\web\Request;
    
    /**
     * This is the model class for table "{{%tax_group}}".
     *
     * @property integer        $id
     * @property boolean        $is_filter
     * @property integer        $level
     * @property integer        $sort
     * @property boolean        $display
     * @property boolean        $is_menu
     * @property string         $remote_id
     * @property TaxOption[]    $taxOptions
     * @property Category[]     $categories
     * @property TaxOption[]    $options
     * @property TaxOption[]    $customOptions
     * @property string         $alias
     * * From language behavior *
     * @property TaxGroupLang   $lang
     * @property TaxGroupLang[] $langs
     * @property TaxGroupLang   $objectLang
     * @property string         $ownerKey
     * @property string         $langKey
     * @property TaxGroupLang[] $modelLangs
     * @property bool           $transactionStatus
     * @method string           getOwnerKey()
     * @method void             setOwnerKey( string $value )
     * @method string           getLangKey()
     * @method void             setLangKey( string $value )
     * @method ActiveQuery      getLangs()
     * @method ActiveQuery      getLang( integer $language_id )
     * @method TaxGroupLang[]    generateLangs()
     * @method void             loadLangs( Request $request )
     * @method bool             linkLangs()
     * @method bool             saveLangs()
     * @method bool             getTransactionStatus()
     * * End language behavior *
     */
    class TaxGroup extends ActiveRecord
    {
        
        const GROUP_PRODUCT = 0;
        const GROUP_VARIANT = 1;
        
        /**
         * @var TaxOption[] $options
         */
        protected $customOptions = [];
        
        /**
         * @inheritdoc
         */
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
        public static function tableName()
        {
            return 'tax_group';
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [
                        'is_filter',
                        'display',
                        'is_menu',
                    ],
                    'boolean',
                ],
                [
                    [
                        'level',
                        'sort',
                    ],
                    'integer',
                ],
                [
                    [ 'categories' ],
                    'safe',
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'        => 'Tax Group ID',
                'is_filter' => 'Use in filter',
                'sort'      => 'Sort',
                'display'   => 'Display',
                'is_menu'   => 'Отображать в меню',
            ];
        }
        
        /**
         * @return ActiveQuery
         */
        public function getCategories()
        {
            return $this->hasMany(Category::className(), [ 'id' => 'category_id' ])
                        ->viaTable('tax_group_to_category', [ 'tax_group_id' => 'id' ]);
        }
        
        /**
         * Set categories to override tax_group_to_category table data
         *
         * @param int[] $values
         */
        public function setCategories(array $values)
        {
            $this->categories = $values;
        }
        
        /**
         * @inheritdoc
         */
        public function afterSave($insert, $changedAttributes)
        {
            parent::afterSave($insert, $changedAttributes);
            $this->unlinkAll('categories', true);
            $categories = [];
            if (!empty( $this->categories )) {
                $categories = Category::findAll($this->categories);
            }
            foreach ($categories as $category) {
                $this->link('categories', $category);
            }
        }
        
        /**
         * @return ActiveQuery
         */
        public function getTaxOptions()
        {
            return $this->hasMany(TaxOption::className(), [ 'tax_group_id' => 'id' ])
                        ->inverseOf('taxGroup');
        }
        
        /**
         * Synonim for getTaxOptions()
         *
         * @see TaxGroup::getTaxOptions()
         * @return \yii\db\ActiveQuery
         */
        public function getOptions()
        {
            return $this->getTaxOptions();
        }
        
        /**
         * Get customOptins that were filled dynamically.
         * If $fillDefault is true then fill $customOptions with TaxOptions for current TaxGroup
         *
         * @param bool $fillDefault
         *
         * @return TaxOption[]
         */
        public function getCustomOptions(bool $fillDefault = false): array
        {
            if ($fillDefault && empty( $this->custom_options )) {
                $this->customOptions = $this->getTaxOptions()
                                            ->with('lang')
                                            ->all();
            }
            return $this->customOptions;
        }
        
        /**
         * Set customOptions
         *
         * @param TaxOption[] $value
         */
        public function setCustomOptions(array $value)
        {
            foreach ($value as $item) {
                if (!( $item instanceof TaxOption )) {
                    throw new InvalidValueException('All elements must be instances of ' . TaxOption::className());
                }
            }
            $this->customOptions = $value;
        }
        
        /**
         * Get default lang alias
         *
         * @return string
         */
        public function getAlias()
        {
            $default_lang = Language::getDefaultLanguage();
            /**
             * @var TaxGroupLang $lang
             */
            $lang = $this->getLang($default_lang->id)
                         ->one();
            return $lang->alias;
        }
    }
