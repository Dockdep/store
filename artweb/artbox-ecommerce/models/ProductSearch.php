<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    use yii\db\ActiveQuery;
    
    /**
     * ProductSearch represents the model behind the search form about
     * `artweb\artbox\ecommerce\models\Product`.
     */
    class ProductSearch extends Product
    {
        
        public $categoryId;
        
        public $productName;
        public $variantSku;
        public $variantCount;
        
        public function behaviors()
        {
            $behaviors = parent::behaviors();
            if (isset( $behaviors[ 'language' ] )) {
                unset( $behaviors[ 'language' ] );
            }
            return $behaviors;
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [
                        'productName',
                        'variantSku'
                    ],
                    'safe',
                ],
                [
                    [
                        'brand_id',
                        'id',
                        'categoryId',
                    ],
                    'integer',
                ],
                [
                    [
                        'is_top',
                        'is_new',
                        'is_discount',
                    ],
                    'boolean',
                ],
            ];
        }
        
        public function attributeLabels()
        {
            $labels = parent::attributeLabels();
            $new_labels = [
                'categoryId'   => 'Category ID',
                'brand_id'     => 'Brand ID',
                'productName'  => 'Product name',
                'variantCount' => 'Variant count',
            ];
            return array_merge($labels, $new_labels);
        }
        
        /**
         * @inheritdoc
         */
        public function scenarios()
        {
            // bypass scenarios() implementation in the parent class
            return Model::scenarios();
        }
        
        /**
         * Creates data provider instance with search query applied
         *
         * @param array $params
         *
         * @return ActiveDataProvider
         */
        public function search($params)
        {
            $query = Product::find();
            $query->select(
                [
                    'product.*',
                ]
            );
            
            $query->joinWith(
                [
                    'categories',
                    'lang',
                ]
            )//                  ->joinWith(
                //                      [
                //                          'brand.lang',
                //                      ]
                //                  )
                              /**
                               * @var ActiveQuery $query
                               */
                  ->joinWith('variants');
            
            $query->groupBy(
                [
                    'product.id',
                    'product_variant.id',
                ]
            );
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $query,
                ]
            );
            
            $dataProvider->setSort(
                [
                    'attributes' => [
                        'id',
                        'productName'  => [
                            'asc'  => [ 'product_lang.title' => SORT_ASC ],
                            'desc' => [ 'product_lang.title' => SORT_DESC ],
                        ],
                        'variantSku',
                        //                        'brand_id'     => [
                        //                            'asc'     => [ 'brand_lang.title' => SORT_ASC ],
                        //                            'desc'    => [ 'brand_lang.title' => SORT_DESC ],
                        //                            'default' => SORT_DESC,
                        //                        ],
                    ],
                ]
            );
            
            $this->load($params);
            
            if (!$this->validate()) {
                // uncomment the following line if you do not want to return any records when validation fails
                // $query->where('0=1');
                return $dataProvider;
            }
            
            if (isset( $this->is_top )) {
                $query->andWhere(
                    [
                        'is_top' => (bool) $this->is_top,
                    ]
                );
            }
            if (isset( $this->is_new )) {
                $query->andWhere(
                    [
                        'is_new' => (bool) $this->is_new,
                    ]
                );
            }
            if (isset( $this->is_discount )) {
                $query->andWhere(
                    [
                        'is_discount' => (bool) $this->is_discount,
                    ]
                );
            }
            $query->andFilterWhere(
                [
                    //                    'product.brand_id'             => $this->brand_id,
                    'product.id'                   => $this->id,
                    'product_category.category_id' => $this->categoryId,
                ]
            );
            $query->andFilterWhere(
                [
                    'like',
                    'product_lang.title',
                    $this->productName,
                ]
            );
            $query->andFilterWhere(
                [
                    'ilike',
                    'product_variant.sku',
                    $this->variantSku
                ]
            );
            
            return $dataProvider;
        }
    }
