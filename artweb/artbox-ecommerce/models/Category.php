<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use artweb\artbox\behaviors\SaveImgBehavior;
    use artweb\artbox\components\artboxtree\ArtboxTreeBehavior;
    use artweb\artbox\language\behaviors\LanguageBehavior;
    use artweb\artbox\language\models\Language;
    use Yii;
    use yii\base\InvalidParamException;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;
    use yii\db\Query;
    use yii\web\Request;
    
    /**
     * This is the model class for table "category".
     *
     * @todo Write doc for ArtboxTreeBehavior
     * @property integer           $id
     * @property integer           $sort
     * @property integer           $sort2
     * @property integer           $status
     * @property integer           $remote_id
     * @property integer           $parent_id
     * @property string            $path
     * @property integer           $depth
     * @property string            $image
     * @property string            $icon
     * @property integer           $product_unit_id
     * @property Product[]         $products
     * @property ProductUnit       $productUnit
     * @property ProductCategory[] $productCategories
     * @property Brand[]           $brands
     * @property TaxGroup[]        $taxGroups
     * @property Category[]        $siblings
     * @property Category          $parentAR
     *       * From language behavior *
     * @property CategoryLang      $lang
     * @property CategoryLang[]    $langs
     * @property CategoryLang      $objectLang
     * @property string            $ownerKey
     * @property string            $langKey
     * @property CategoryLang[]    $modelLangs
     * @property bool              $transactionStatus
     * @method string           getOwnerKey()
     * @method void             setOwnerKey( string $value )
     * @method string           getLangKey()
     * @method void             setLangKey( string $value )
     * @method ActiveQuery      getLangs()
     * @method ActiveQuery      getLang( integer $language_id )
     * @method CategoryLang[]    generateLangs()
     * @method void             loadLangs( Request $request )
     * @method bool             linkLangs()
     * @method bool             saveLangs()
     * @method bool             getTransactionStatus()
     *       * End language behavior *
     *       * From SaveImgBehavior
     * @property string|null       $imageFile
     * @property string|null       $imageUrl
     * @method string|null getImageFile( int $field )
     * @method string|null getImageUrl( int $field, bool $dummy )
     *       * End SaveImgBehavior
     */
    class Category extends ActiveRecord
    {
        
        public function behaviors()
        {
            return [
                'artboxtree' => [
                    'class'        => ArtboxTreeBehavior::className(),
                    'keyNameGroup' => null,
                    'keyNamePath'  => 'path',
                ],
                'language'   => [
                    'class' => LanguageBehavior::className(),
                ],
                [
                    'class'  => SaveImgBehavior::className(),
                    'fields' => [
                        [
                            'name'      => 'image',
                            'directory' => 'categories',
                        ],
                    ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'category';
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [
                        'parent_id',
                        'depth',
                        'product_unit_id',
                        'sort',
                        'sort2',
                    ],
                    'integer',
                ],
                [
                    [
                        'path',
                    ],
                    'string',
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'              => Yii::t('product', 'Category ID'),
                'parent_id'       => Yii::t('product', 'Parent ID'),
                'path'            => Yii::t('product', 'Path'),
                'depth'           => Yii::t('product', 'Depth'),
                'image'           => Yii::t('product', 'Image'),
                'imageUrl'        => Yii::t('product', 'Image'),
                'product_unit_id' => Yii::t('product', 'Product Unit ID'),
                'remote_id'       => Yii::t('product', 'Remote ID'),
                'sort'            => Yii::t('product', 'Порядок вывода'),
                'sort2'           => Yii::t('product', 'Порядок вывода на главной'),
                'status'          => "Статус"
            ];
        }
        
        public static function find()
        {
            return new CategoryQuery(get_called_class());
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getProductUnit()
        {
            return $this->hasOne(ProductUnit::className(), [ 'id' => 'product_unit_id' ]);
        }
        
        /**
         * @return ActiveQuery
         */
        public function getProducts()
        {
            return $this->hasMany(Product::className(), [ 'id' => 'product_id' ])
                        ->viaTable('product_category', [ 'category_id' => 'id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getProductCategories()
        {
            return $this->hasMany(ProductCategory::className(), [ 'category_id' => 'id' ]);
        }
        
        /**
         * Get all brands for Category query
         *
         * @return ActiveQuery
         */
        public function getBrands()
        {
            return $this->hasMany(Brand::className(), [ 'id' => 'brand_id' ])
                        ->via('products');
        }
        
        /**
         * Get Tax Groups by level
         * * 0 for Product
         * * 1 for ProductVariant
         *
         * @param int $level
         *
         * @return ActiveQuery
         */
        public function getTaxGroupsByLevel(int $level)
        {
            if ($level !== 0 || $level !== 1) {
                throw new InvalidParamException('Level supports only 0 and 1 values');
            }
            return $this->hasMany(TaxGroup::className(), [ 'id' => 'tax_group_id' ])
                        ->viaTable('tax_group_to_category', [ 'category_id' => 'id' ])
                        ->andWhere([ 'level' => $level ]);
        }
        
        /**
         * Леша найди путь как убрать это, мб в базу записать просто по умолчанию значение и notnull
         *
         * @param bool $insert
         *
         * @return bool
         */
        public function beforeSave($insert)
        {
            if (parent::beforeSave($insert)) {
                
                if (empty( $this->parent_id )) {
                    $this->parent_id = 0;
                }
                
                return true;
            }
            return false;
        }
        
        /**
         * Get query for activefilter for current category
         *
         * @return Query
         */
        public function getActiveFilters()
        {
            $language_id = Language::getCurrent()->id;
            $query1 = ( new Query() )->distinct()
                                     ->select(
                                         [
                                             'option_id',
                                         ]
                                     )
                                     ->from('tax_option')
                                     ->innerJoin(
                                         'product_variant_option',
                                         'tax_option.id = product_variant_option.option_id'
                                     )
                                     ->innerJoin('tax_group', 'tax_group.id = tax_option.tax_group_id')
                                     ->innerJoin(
                                         'product_variant',
                                         'product_variant.id = product_variant_option.product_variant_id'
                                     )
                                     ->innerJoin('product', 'product.id = product_variant.product_id')
                                     ->innerJoin('product_category', 'product_category.product_id = product.id')
                                     ->innerJoin(
                                         'tax_group_to_category',
                                         'tax_group.id = tax_group_to_category.tax_group_id'
                                     )
                                     ->where(
                                         [
                                             'product_category.category_id'      => $this->id,
                                             'tax_group.is_filter'               => true,
                                             'tax_group_to_category.category_id' => $this->id,
                                         ]
                                     )
                                     ->andWhere(
                                         [
                                             '!=',
                                             'product_variant.stock',
                                             0,
                                         ]
                                     );
            
            $query2 = ( new Query() )->distinct()
                                     ->select(
                                         [
                                             'option_id',
                                         ]
                                     )
                                     ->from('tax_option')
                                     ->innerJoin(
                                         'product_option',
                                         'tax_option.id = product_option.option_id'
                                     )
                                     ->innerJoin('tax_group', 'tax_group.id = tax_option.tax_group_id')
                                     ->innerJoin('product', 'product.id = product_option.product_id')
                                     ->innerJoin('product_category', 'product_category.product_id = product.id')
                                     ->innerJoin('product_variant', 'product_variant.product_id = product.id')
                                     ->innerJoin(
                                         'tax_group_to_category',
                                         'tax_group.id = tax_group_to_category.tax_group_id'
                                     )
                                     ->where(
                                         [
                                             'product_category.category_id'      => $this->id,
                                             'tax_group.is_filter'               => true,
                                             'tax_group_to_category.category_id' => $this->id,
                                         ]
                                     )
                                     ->andWhere(
                                         [
                                             '!=',
                                             'product_variant.stock',
                                             0,
                                         ]
                                     );
            $query3 = ( new Query() )->select(
                [
                    'tax_option.*',
                    'tax_option.id as tax_option_id',
                    'tax_option_lang.alias as option_alias',
                    'tax_group_lang.alias as group_alias',
                    'tax_group_lang.title as title',
                    'tax_option_lang.value as value',
                    'tax_option.sort AS tax_option_sort',
                    'tax_group.sort AS tax_group_sort',
                ]
            )
                                     ->from([ 'tax_option' ])
                                     ->where([ 'tax_option.id' => $query1->union($query2) ])
                                     ->innerJoin('tax_group', 'tax_group.id = tax_option.tax_group_id')
                                     ->innerJoin('tax_option_lang', 'tax_option.id = tax_option_lang.tax_option_id')
                                     ->innerJoin('tax_group_lang', 'tax_group.id = tax_group_lang.tax_group_id')
                                     ->andWhere([ 'tax_option_lang.language_id' => $language_id ])
                                     ->andWhere([ 'tax_group_lang.language_id' => $language_id ])
                                     ->orderBy('tax_option.sort, tax_group.sort');
            return $query3;
        }
        
        /**
         * Get query to get all TaxGroup for current Category
         *
         * @return ActiveQuery
         */
        public function getTaxGroups()
        {
            return $this->hasMany(TaxGroup::className(), [ 'id' => 'tax_group_id' ])
                        ->viaTable('tax_group_to_category', [ 'category_id' => 'id' ]);
        }
        
        /**
         * Get query to obtain siblings of current category (categories with same parent)
         *
         * @return ActiveQuery
         */
        public function getSiblings()
        {
            return $this->hasMany($this::className(), [ 'parent_id' => 'parent_id' ])
                        ->andWhere(
                            [
                                'not',
                                [ 'id' => $this->id ],
                            ]
                        );
        }
        
        /**
         * Return Active query to obtain parent category
         *
         * @return ActiveQuery
         */
        public function getParentAR()
        {
            return $this->hasOne(self::className(), [ 'id' => 'parent_id' ]);
        }
    }
