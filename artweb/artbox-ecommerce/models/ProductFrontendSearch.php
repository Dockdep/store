<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use artweb\artbox\ecommerce\helpers\CatalogFilterHelper;
    use artweb\artbox\ecommerce\helpers\FilterHelper;
    use artweb\artbox\ecommerce\models\Category;
    use artweb\artbox\language\models\Language;
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    use yii\data\ArrayDataProvider;
    use yii\data\Sort;
    use yii\db\ActiveQuery;
    
    use artweb\artbox\ecommerce\models\Product;
    use artweb\artbox\ecommerce\models\ProductVariant;
    use yii\helpers\ArrayHelper;

    class ProductFrontendSearch extends Product
    {
        
        public $price_interval;
        public $brands;
        
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
                        'price_interval',
                        'brands',
                    ],
                    'safe',
                ],
            ];
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
         * Creates data provider instance with search query applied for frontend
         *
         * @param array $params
         *
         * @return ActiveDataProvider
         */
        public function search($category = null, $params = [], $in_stock = true)
        {
            
            $sort = new Sort(
                [
                    'attributes' => [
                        'name_asc'   => [
                            'asc'     => [ 'product_lang.title' => SORT_ASC ],
                            'desc'    => [ 'product_lang.title' => SORT_ASC ],
                            'default' => SORT_ASC,
                            'label'   => 'имени от А до Я',
                        ],
                        'name_desc'  => [
                            'asc'     => [ 'product_lang.title' => SORT_DESC ],
                            'desc'    => [ 'product_lang.title' => SORT_DESC ],
                            'default' => SORT_DESC,
                            'label'   => 'имени от Я до А',
                        ],
                        'price_asc'  => [
                            'asc'     => [ 'product_variant.price' => SORT_ASC ],
                            'desc'    => [ 'product_variant.price' => SORT_ASC ],
                            'default' => SORT_ASC,
                            'label'   => 'по цене по возрастанию',
                        ],
                        'price_desc' => [
                            'asc'     => [ 'product_variant.price' => SORT_DESC ],
                            'desc'    => [ 'product_variant.price' => SORT_DESC ],
                            'default' => SORT_DESC,
                            'label'   => 'по цене по уменшению',
                        ],
                    ],
                ]
            );
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query'      => $this->getSearchQuery($category, $params, $in_stock)->distinct()
                                            ->select(['product.*','product_variant.price','product_lang.title'])
                                         ->with('variant', 'videos','category.lang')
                                         ->groupBy(
                                             [
                                                 'product_lang.title',
                                                 'product_variant.price',
                                                 'product.id',
                                                 'product_variant.id',
                                             ]
                                         ),
                    'pagination' => [
                        'pageSize' => 9,
                    ],
                    'sort'       => $sort,
                ]
            );
            
            return $dataProvider;
        }


        /**
         * @param Category $category
         * @param array $params
         * @param bool $in_stock
         * @return mixed
         */
        public function getSearchQuery($category, $params = [], $in_stock = true)
        {

            $query = $category->getProducts();

            
            $query->select([ 'product.*' ]);
            $query->joinWith(
                [
                    'lang',
                    'brand.lang',
                    'options',
                ]
            );
            
            if ($in_stock) {
                $query->innerJoinWith(
                    [
                        'enabledVariants' => function ($query) {
                            /**
                             * @var ActiveQuery $query
                             */
                            $query->joinWith('lang')
                                  ->with('images');
                        },
                    ]
                );
            } else {
                $query->innerJoinWith(
                    [
                        'variants' => function ($query) {
                            /**
                             * @var ActiveQuery $query
                             */
                            $query->joinWith('lang')
                                  ->with('images');
                        },
                    ]
                );
            }
            
            $query->groupBy(
                [
                    'product.id',
                    'product_variant.price',
                ]
            );

            $lang = Language::getCurrent();

            /**
             * @var $catalog \yii\elasticsearch\Query;
             */
//            if($params){
//                $catalog = CatalogFilterHelper::setQueryParams($params, $category->id, $lang->id);
//                $catalog->limit(9999);
//                $catalog->createCommand();
//                $result = $catalog->column('id');
//                $query->andWhere(['product.id'=>$result]);
//            }


            return $query;
        }
        
        /**
         * @param Category|null $category
         *
         * @return array
         */
        
        public function priceLimits($category = null, $params = [])
        {
            if (!empty( $category )) {
                /** @var ActiveQuery $query */
                //            $query = $category->getRelations('product_categories');
                $query = $category->getProducts();
            } else {
                $query = Product::find();
            }



            $query->select(['MAX('.ProductVariant::tableName() . '.price) as max', 'MIN('.ProductVariant::tableName() . '.price) as min']);
            $query->joinWith('variant');

            // Price filter fix
            unset( $params[ 'prices' ] );
            $lang = Language::getCurrent();

            /**
             * @var $catalog \yii\elasticsearch\Query;
             */
//            if($params){
//                $catalog = CatalogFilterHelper::setQueryParams($params, $category->id, $lang->id);
//                $catalog->limit(9999);
//                $catalog->createCommand();
//                $result = $catalog->column('id');
//                $query->andWhere(['product.id'=>$result]);
//            }


            return $query->one();
        }
    }