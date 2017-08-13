<?php
    
    namespace frontend\controllers;
    
    use artweb\artbox\ecommerce\helpers\CatalogFilterHelper;
    use artweb\artbox\ecommerce\models\ProductVariantLang;
    use artweb\artbox\language\models\Language;
    use artweb\artbox\ecommerce\helpers\ProductHelper;
    use artweb\artbox\ecommerce\models\ProductVariant;
    use artweb\artbox\ecommerce\models\ProductFrontendSearch;
    use artweb\artbox\models\Page;
    use mihaildev\elfinder\InputFile;
    use Yii;
    use artweb\artbox\ecommerce\models\Brand;
    use artweb\artbox\ecommerce\models\BrandSearch;
    use artweb\artbox\ecommerce\models\Category;
    use artweb\artbox\ecommerce\models\Product;
    use artweb\artbox\ecommerce\models\TaxGroup;
    use yii\data\ActiveDataProvider;
    use yii\helpers\ArrayHelper;
    use yii\helpers\VarDumper;
    use yii\web\NotFoundHttpException;
    
    class CatalogController extends \yii\web\Controller
    {
        
        public function actionSearch()
        {
            // @todo
        }
        
        public function actionCategory()
        {
            /** @var Category $category */
            $category = Yii::$app->request->get('category');
            $filter = Yii::$app->request->get('filters', []);
            $filterCheck = $filter;
            
            $cacheKey = [
                'SiblingsBlock',
                'variations' => [ \Yii::$app->language ],
                'category'   => $category->id,
            ];
            
            if (!$siblings = Yii::$app->cache->get($cacheKey)) {
                
                $siblings = $category->getSiblings()
                                     ->with('lang')
                                     ->all();
                Yii::$app->cache->set($cacheKey, $siblings, 3600 * 24);
                
            }
            
            ProductHelper::addLastCategory($category->id);
            
            $params = [];
            
            $optionsList = ArrayHelper::getColumn(
                TaxGroup::find()
                        ->select('*')
                        ->joinWith('lang')
                        ->where([ 'is_filter' => 'TRUE' ])
                        ->asArray()
                        ->all(),
                'alias'
            );
            
            if (!empty($filter[ 'special' ])) {
                unset($filterCheck[ 'special' ]);
                $params[ 'special' ] = $filter[ 'special' ];
            }
            
            if (!empty($filter[ 'prices' ])) {
                unset($filterCheck[ 'prices' ]);
                $params[ 'prices' ] = $filter[ 'prices' ];
            }
            
            $activeFiltersParams = $filterCheck;
            
            if (!empty($filter[ 'brands' ])) {
                unset($filterCheck[ 'brands' ]);
                $params[ 'brands' ] = $filter[ 'brands' ];
            }
            
            foreach ($optionsList as $optionList) {
                
                if (isset($filter[ $optionList ])) {
                    unset($filterCheck[ $optionList ]);
                    $params[ $optionList ] = $filter[ $optionList ];
                }
                
            }
            
            if (!empty($filterCheck)) {
                $filter = array_diff_key($filter, $filterCheck);
                
                Yii::$app->response->redirect(
                    [
                        'catalog/category',
                        'category' => $category,
                        'filters'  => $filter,
                    ],
                    301
                );
            }
            
            $productModel = new ProductFrontendSearch();
            
            $productProvider = $productModel->search($category, $params);
            
            $cacheKey = [
                'FilterBlock',
                'variations' => [ \Yii::$app->language ],
                'category'   => $category->id,
            ];
            if (!$groups = Yii::$app->cache->get($cacheKey)) {
                $brands = $category->getBrands()
                                   ->joinWith('lang')
                                   ->groupBy(
                                       [
                                           'brand.id',
                                           'brand_lang.title',
                                       ]
                                   )
                                   ->orderBy('title DESC')
                                   ->all();
                
                $groups = $category->getActiveFilters()
                                   ->all();
                
                foreach ($brands as $brand) {
                    array_unshift(
                        $groups,
                        [
                            'group_alias'   => 'brands',
                            'option_alias'  => $brand->lang->alias,
                            'tax_option_id' => $brand->id,
                            'value'         => $brand->lang->title,
                            'alias'         => 'brands',
                            'title'         => 'Бренды',
                        ]
                    );
                }
                
                Yii::$app->cache->set($cacheKey, $groups, 3600 * 24);
                
            }
            
            foreach ($groups as $key => $group) {
                $param = $activeFiltersParams;
                if (isset($param[ $group[ 'group_alias' ] ])) {
                    if (!in_array($group[ 'option_alias' ], $param[ $group[ 'group_alias' ] ])) {
                        $param[ $group[ 'group_alias' ] ][] = $group[ 'option_alias' ];
                    } else {
                        continue;
                    }
                } else {
                    $param = array_merge($param, [ $group[ 'group_alias' ] => [ $group[ 'option_alias' ] ] ]);
                }
                if (isset($filter[ 'special' ])) {
                    $param[ 'special' ] = $filter[ 'special' ];
                }
                $lang = Language::getCurrent();
                /**
                 * @var $catalog \yii\elasticsearch\Query;
                 */
                $catalog = CatalogFilterHelper::setQueryParams($param, $category->id, $lang->id);
                $catalog->createCommand();
                $catalog = $catalog->search();
                
                $groups[ $key ] = array_merge($groups[ $key ], [ 'count' => $catalog[ 'hits' ][ 'total' ] ]);
                
            }
            
            $groups = ArrayHelper::index($groups, NULL, 'title');
            
            $priceLimits = $productModel->priceLimits($category, $params);
            
            return $this->render(
                'products',
                [
                    'category'        => $category,
                    'filter'          => $filter,
                    'params'          => $params,
                    'productModel'    => $productModel,
                    'productProvider' => $productProvider,
                    'groups'          => $groups,
                    'priceLimits'     => $priceLimits,
                    'siblings'        => $siblings,
                ]
            );
            
        }
        
        public function actionProduct($product, $variant)
        {
            /**
             * @var Product          $product
             * @var ProductVariant   $variant
             * @var ProductVariant[] $allSameNameVariants
             */
            $product = Product::find()
                              ->joinWith('lang')
                              ->where(
                                  [
                                      'product_lang.alias' => $product,
                                  ]
                              )
                              ->one();
            if (!empty($product)) {
                $variant = $product->getVariant()
                                   ->with('variantStocks')
                                   ->joinWith('lang')
                                   ->where([ 'sku' => $variant ])
                                   ->one();
            }
            
            $size = $product->getSize();
            
            $option = $variant->getOptions()
                              ->joinWith('group')
                              ->with('lang')
                              ->orderBy([ 'tax_group.position' => SORT_DESC ])
                              ->one();
            
            if (empty($option)) {
                if (preg_match("@.*\.(jpg|png|gif)@i", $variant->lang->title)) {
                    $specialOption = '';
                } else {
                    $specialOption = ' ' . $variant->lang->title;
                }
            } else {
                if (preg_match("@.*\.(jpg|png|gif)@i", $variant->lang->title)) {
                    $specialOption = ' (' . $option->lang->value . ')';
                } else {
                    $specialOption = ' ' . $variant->lang->title . ' (' . $option->lang->value . ')';
                }
            }
            
            if (empty($product) || empty($variant)) {
                throw new NotFoundHttpException();
            }
            
            $category = NULL;
            if (!empty($last_category = ProductHelper::getLastCategory())) {
                $category = $product->getCategory()
                                    ->where([ 'id' => $last_category ])
                                    ->one();
            }
            if (empty($category)) {
                $category = $product->category;
            }
            
            $colorVariants = $product->getVariants()
                                     ->select('product_variant.*', 'DISTINCT ON (product_variant_lang.title)')
                                     ->joinWith('lang')
                                     ->andWhere(
                                         [
                                             '!=',
                                             'product_variant_lang.title',
                                             $variant->lang->title,
                                         ]
                                     )
                                     ->andWhere(
                                         [
                                             '!=',
                                             'product_variant.stock',
                                             0,
                                         ]
                                     )
                                     ->all();
            
            $allSameNameVariants = $product->getVariants()
                                           ->joinWith('lang')
                                           ->andWhere(
                                               [
                                                   'product_variant_lang.title' => $variant->lang->title,
                                               ]
                                           )
                                           ->andWhere(
                                               [
                                                   '!=',
                                                   'product_variant.stock',
                                                   0,
                                               ]
                                           )
                                           ->all();
            
            $tabVariants = [];
            
            foreach ($allSameNameVariants as $sameNameVariant) {
                $properties = $sameNameVariant->getProperties(
                    [
                        [ 'position' => 1 ],
                    ]
                );
                
                foreach ($properties as $property) {
                    $sameNameVariant->customOption[ $property->lang->alias ] = $sameNameVariant->getOption(
                        [ [ 'tax_group_id' => $property->id ] ]
                    )
                                                                                               ->joinWith('lang')
                                                                                               ->one();
                    $tabVariants[ $property->lang->alias ][] = $sameNameVariant;
                    $tabVariants[ $property->lang->alias ][ 'title' ] = $property->lang->title;
                    $sortKey = $property->lang->alias;
                }
                if (!empty($tabVariants)) {
                    ArrayHelper::multisort(
                        $tabVariants[ $sortKey ],
                        function($item) use ($sortKey) {
                            if (is_object($item)) {
                                return $item->customOption[ $sortKey ]->sort;
                            } else {
                                return 100;
                            }
                        }
                    );
                }
            }
            
            $listVariants = [];
            
            foreach ($allSameNameVariants as $sameNameVariant) {
                $properties = $sameNameVariant->getProperties(
                    [
                        [ 'position' => 2 ],
                    ]
                );
                
                foreach ($properties as $property) {
                    $sameNameVariant->customOption[ $property->lang->alias ] = $sameNameVariant->getOption(
                        [ [ 'tax_group_id' => $property->id ] ]
                    )
                                                                                               ->joinWith('lang')
                                                                                               ->one();
                    $listVariants[ $property->lang->alias ][] = $sameNameVariant;
                    $listVariants[ $property->lang->alias ][ 'title' ] = $property->lang->title;
                    $sortKey = $property->lang->alias;
                }
                if (!empty($listVariants)) {
                    ArrayHelper::multisort(
                        $listVariants[ $sortKey ],
                        function($item) use ($sortKey) {
                            if (is_object($item)) {
                                return $item->customOption[ $sortKey ]->sort;
                            } else {
                                return 100;
                            }
                        }
                    );
                }
            }
            
            $variantCharacteristics = $variant->getCharacteristic();
            $productCharacteristics = $product->getCharacteristic();
            $characteristics = array_merge($variantCharacteristics, $productCharacteristics);
            $comments = $product->getComments()
                                ->innerJoinWith('rating')
                                ->all();
            $product->recalculateRating();
            
            ProductHelper::addLastProducts($product->id);
            
            $pages = Page::find()
                         ->with('lang')
                         ->where(
                             [
                                 'id' => [
                                     4,
                                     8,
                                 ],
                             ]
                         )
                         ->indexBy('id')
                         ->all();
            
            return $this->render(
                'card',
                [
                    'product'         => $product,
                    'variant'         => $variant,
                    'category'        => $category,
                    'colorVariants'   => $colorVariants,
                    'tabVariants'     => $tabVariants,
                    'listVariants'    => $listVariants,
                    'characteristics' => $characteristics,
                    'comments'        => $comments,
                    'pages'           => $pages,
                    'specialOption'   => $specialOption,
                    'size'            => $size,
                ]
            );
        }
        
        public function actionBrands()
        {
            $dataProvider = new ActiveDataProvider(
                [
                    'query'      => Brand::find()
                                         ->orderBy('name'),
                    'pagination' => [
                        'pageSize' => -1,
                    ],
                ]
            );
            
            return $this->render(
                'brands',
                [
                    'dataProvider' => $dataProvider,
                ]
            );
        }
        
        public function actionBrand($brand)
        {
            $brand = BrandSearch::findByAlias($brand);
            
            $params = [
                'brands' => $brand->brand_id,
            ];
            
            $productModel = new ProductFrontendSearch();
            $productProvider = $productModel->search(NULL, $params);
            
            $priceLimits = $productModel->priceLimits(NULL, $params);
            
            return $this->render(
                'brand',
                [
                    'productModel'    => $productModel,
                    'productProvider' => $productProvider,
                    'brand'           => $brand,
                    'priceLimits'     => $priceLimits,
                ]
            );
        }
        
    }
