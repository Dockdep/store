<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use artweb\artbox\behaviors\MultipleImgBehavior;
    use artweb\artbox\behaviors\SaveMultipleFileBehavior;
    use artweb\artbox\language\behaviors\LanguageBehavior;
    use frontend\models\Catalog;
    use Yii;
    use yii\base\InvalidParamException;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;
    use yii\web\Request;
    
    /**
     * This is the model class for table "product_variant".
     *
     * @property integer              $id
     * @property integer              $product_id
     * @property integer              $remote_id
     * @property string               $sku
     * @property double               $price
     * @property double               $price_old
     * @property double               $stock
     * @property string               $fullname
     * @property TaxOption[]          $options
     * @property TaxGroup[]           $groups
     * @property TaxOption[]          $filterOptions
     * @property Product              $product
     * @property Category[]           $categories
     * @property Category             $category
     * @property TaxOption[]          $filters
     * @property ProductStock[]       $productStocks
     * @property int                  $quantity
     * @property ProductStock[]       $variantStocks
     * @property Stock[]              $stocks
     * @property TaxGroup[]           $properties
     * @property TaxGroup[]           $taxGroupsByLevel
     * @property string               $size
     * @property string               $imageUrl
     *       * From language behavior *
     * @property ProductVariantLang   $lang
     * @property ProductVariantLang[] $langs
     * @property ProductVariantLang   $objectLang
     * @property string               $ownerKey
     * @property string               $langKey
     * @property ProductVariantLang[] $modelLangs
     * @property bool                 $transactionStatus
     * @method string           getOwnerKey()
     * @method void             setOwnerKey( string $value )
     * @method string           getLangKey()
     * @method void             setLangKey( string $value )
     * @method ActiveQuery      getLangs()
     * @method ActiveQuery      getLang( integer $language_id )
     * @method ProductVariantLang[]    generateLangs()
     * @method void             loadLangs( Request $request )
     * @method bool             linkLangs()
     * @method bool             saveLangs()
     * @method bool             getTransactionStatus()
     *       * End language behavior *
     *       * From multipleImage behavior
     * @property ProductImage         $image
     * @property ProductImage[]       $images
     * @property array                $imagesConfig
     * @method ActiveQuery getImage()
     * @method ActiveQuery getImages()
     * @method array getImagesConfig()
     * @method array getImagesHTML( string $preset )
     *       * End multipleImage behavior
     */
    class ProductVariant extends ActiveRecord
    {
        
        public $customOption = [];

        /**
         * @var int[] $options
         */
        private $options;
        
        /** @var array $_images */
        public $imagesUpload = [];
        
        /**
         * @var array $stocks
         */
        protected $stocks = [];
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'product_variant';
        }
        
        public function behaviors()
        {
            return [
                'language'      => [
                    'class' => LanguageBehavior::className(),
                ],
                'images'        => [
                    'class'     => SaveMultipleFileBehavior::className(),
                    'name'      => 'imagesUpload',
                    'directory' => 'products',
                    'column'    => 'image',
                    'links'     => [
                        'product_id' => 'product_id',
                        'id'         => 'product_variant_id',
                    ],
                    'model'     => ProductImage::className(),
                ],
                'multipleImage' => [
                    'class'  => MultipleImgBehavior::className(),
                    'links'  => [
                        'product_variant_id' => 'id',
                    ],
                    'model'  => ProductImage::className(),
                    'config' => [
                        'caption'       => 'image',
                        'delete_action' => 'variant/delete-image',
                        'id'            => 'id',
                    ],
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
                        'product_id',
                    ],
                    'integer',
                ],
                [
                    [
                        'price',
                        'price_old',
                        'stock',
                    ],
                    'number',
                ],
                [
                    [
                        'sku',
                    ],
                    'string',
                    'max' => 255,
                ],
                [
                    [
                        'options',
                    ],
                    'safe',
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
                'id'              => Yii::t('product', 'Product Variant ID'),
                'product_id'      => Yii::t('product', 'Product ID'),
                'sku'             => Yii::t('product', 'Sku'),
                'price'           => Yii::t('product', 'Price'),
                'price_old'       => Yii::t('product', 'Price Old'),
                'stock'           => Yii::t('product', 'Stock'),
                'stock_caption'   => Yii::t('product', 'Stock'),
                'image'           => Yii::t('product', 'Image'),
                'images'          => Yii::t('product', 'Images'),
            ];
        }

        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getProduct()
        {
            return $this->hasOne(Product::className(), [ 'id' => 'product_id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getProductStocks()
        {
            return $this->hasMany(ProductStock::className(), [ 'product_variant_id' => 'id' ]);
        }
        
        /**
         * Get qunatity for current ProductVariant
         * If $recalculate set to true will recalculate stock via product_stock table
         *
         * @param bool $recalculate
         *
         * @return int
         */
        public function getQuantity(bool $recalculate = false): int
        {
            if (!$recalculate) {
                return $this->stock;
            } else {
                $quantity = $this->getProductStocks()
                                 ->sum('quantity');
                if (empty($quantity)) {
                    $this->stock = 0;
                } else {
                    $this->stock = (int) $quantity;
                }
                $this->save(false, [ 'stock' ]);
                return $this->stock;
            }
        }
        
        /**
         * Get ProductStocks query woth preloaded Stocks for current ProductVariant
         * **Used in dynamic fields in product variant form**
         *
         * @return ActiveQuery
         */
        public function getVariantStocks()
        {
            return $this->getProductStocks()
                        ->joinWith('stock');
        }
        
        /**
         * @return ActiveQuery
         */
        public function getStocks()
        {
            return $this->hasMany(Stock::className(), [ 'id' => 'stock_id' ])
                        ->via('productStocks');
        }
        
        /**
         * @return ActiveQuery
         */
        public function getOptions()
        {
            return $this->hasMany(TaxOption::className(), [ 'id' => 'option_id' ])
                        ->viaTable('product_variant_option', [ 'product_variant_id' => 'id' ]);
        }

        /**
         * Get TaxGroup for current Product
         *
         * @return ActiveQuery
         */
        public function getGroups()
        {
            return $this->hasMany(TaxGroup::className(), [ 'id' => 'tax_group_id' ])
                ->via('options');
        }

        /**
         * Get TaxOptions query for current ProductVariant that will by used in filter
         *
         * @return ActiveQuery
         */
        public function getFilterOptions()
        {
            return $this->getOptions()
                ->joinWith(['lang','taxGroup'])
                ->where(['is_filter' => true]);
        }

        /**
         * Get one variant's option whith needed conditions, or random if condition is empty
         *
         * @param array $conditions
         *
         * @return ActiveQuery
         */
        public function getOption(array $conditions = [])
        {
            $query = $this->hasOne(TaxOption::className(), [ 'id' => 'option_id' ])
                          ->viaTable('product_variant_option', [ 'product_variant_id' => 'id' ]);
            foreach ($conditions as $condition) {
                if (!empty($condition) && is_array($condition)) {
                    $query->andFilterWhere($condition);
                }
            }
            
            return $query;
        }
        
        /**
         * Get TaxOptions with preloaded TaxGroups for current ProductVariant
         *
         * @return ActiveQuery
         */
        public function getFilters()
        {
            return $this->getOptions()
                        ->joinWith('taxGroup.lang')
                        ->joinWith('lang');
        }
        
        /**
         * Get Product title concanated with current ProductVariant title
         *
         * @return string
         */
        public function getFullname(): string
        {
            return $this->product->lang->title . ' ' . $this->lang->title;
        }
        
        /**
         * Set Options to override previous
         *
         * @param int[] $values
         */
        public function setOptions($values)
        {
            $this->options = $values;
        }

        /**
         * Get all TaxGroups for current ProductVariant filled with $customOptions that satisfy current ProductVariant
         *
         * @param array $conditions
         *
         * @return \artweb\artbox\ecommerce\models\TaxGroup[]
         */
        public function getProperties(array $conditions = [])
        {
            $groups = $options = [];
            foreach ($this->getOptions()
                          ->with('lang')
                          ->all() as $option) {
                /**
                 * @var TaxOption $option
                 */
                $options[ $option->tax_group_id ][] = $option;
            }
            $query = TaxGroup::find()
                             ->where([ 'tax_group.id' => array_keys($options) ])
                             ->orderBy([ 'sort' => SORT_ASC ])
                             ->with('lang');
            
            if (!empty($conditions)) {
                foreach ($conditions as $condition) {
                    $query->andFilterWhere($condition);
                }
            }
            foreach ($query->all() as $group) {
                /**
                 * @var TaxGroup $group
                 */
                if (!empty($options[ $group->id ])) {
                    $group->customOptions = $options[ $group->id ];
                    $groups[] = $group;
                }
            }
            return $groups;
        }

        /**
         * Get characteristic from TaxGroups for current ProductVariant filled with $customOptions that satisfy current Product
         *
         * @return TaxGroup[]
         */
        public function getCharacteristic(): array
        {
            $groups = $options = [];
            foreach ($this->getOptions()
                         ->with('lang')
                         ->all() as $option) {
                /**
                 * @var TaxOption $option
                 */
                $options[ $option[ 'tax_group_id' ] ][] = $option;
            }
            foreach (TaxGroup::find()
                         ->where(['is_menu'=>true])
                         ->andWhere([ 'id' => array_keys($options) ])
                         ->with('lang')
                         ->all() as $group) {
                /**
                 * @var TaxGroup $group
                 */
                if (!empty($options[ $group->id ])) {
                    $group->customOptions = $options[ $group->id ];
                    $groups[] = $group;
                }
            }
            return $groups;
        }
        
        /**
         * Set stocks to override existing in product_stock table
         *
         * @param mixed $stocks
         */
        public function setStocks($stocks)
        {
            $this->stocks = (array) $stocks;
        }
        
        /**
         * @return ActiveQuery
         */
        public function getCategory()
        {
            return $this->hasOne(Category::className(), [ 'id' => 'category_id' ])
                        ->viaTable('product_category', [ 'product_id' => 'product_id' ]);
        }
        
        /**
         * @return ActiveQuery
         */
        public function getCategories()
        {
            return $this->hasMany(Category::className(), [ 'id' => 'category_id' ])
                        ->viaTable('product_category', [ 'product_id' => 'product_id' ]);
        }
        
        /**
         * Get TaxGroups query for current ProductVariant according to level
         * * 0 - Product Tax Groups
         * * 1 - ProductVariant Tax Groups
         *
         * @param int $level
         *
         * @return ActiveQuery
         * @throws InvalidParamException
         */
        public function getTaxGroupsByLevel(int $level = 0)
        {
            return $this->product->getTaxGroupsByLevel($level);
        }
        
        public function afterSave($insert, $changedAttributes)
        {
            parent::afterSave($insert, $changedAttributes);

            if (!empty($this->options)) {
                $options = TaxOption::findAll($this->options);
                $this->unlinkAll('options', true);
                foreach ($options as $option) {
                    $this->link('options', $option);
                }
            }
            
            if (!empty($this->stocks)) {
                ProductStock::deleteAll([ 'product_variant_id' => $this->id ]);
                foreach ($this->stocks as $id => $quantity) {
                    /**
                     * @var ProductStock $productStock
                     */
                    $productStock = ProductStock::find()
                                                ->where(
                                                    [
                                                        'product_variant_id' => $this->id,
                                                        'stock_id'           => $id,
                                                    ]
                                                )
                                                ->one();
                    $productStock->quantity = $quantity;
                    $productStock->save();
                }
            }
        }

        /**
         * @return string
         */
        public function getSize()
        {
            $option = $this->getOptions()
                           ->with('lang')
                           ->joinWith('group')
                           ->where(
                               [
                                   'tax_group.position' => 1,
                               ]
                           )
                           ->one();
            if (empty($option)) {
                return '';
            } else {
                return $option->lang->value;
            }
        }

        public function beforeSave($insert)
        {
            if (parent::beforeSave($insert)) {

                return true;
            } else {
                return false;
            }
        }

        public function beforeDelete()
        {
            if (parent::beforeDelete()) {
                return true;
            } else {
                return false;
            }
        }
    }
