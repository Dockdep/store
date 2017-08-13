<?php

    namespace artweb\artbox\ecommerce\helpers;

    use artweb\artbox\ecommerce\models\BrandLang;
    use artweb\artbox\ecommerce\models\CategoryLang;
    use artweb\artbox\ecommerce\models\Product;
    use artweb\artbox\ecommerce\models\ProductLang;
    use artweb\artbox\ecommerce\models\ProductVariant;
    use artweb\artbox\ecommerce\models\ProductVariantLang;
    use artweb\artbox\ecommerce\models\TaxGroup;
    use yii\base\Object;
    use yii\db\ActiveQuery;
    use yii\db\Query;
    use yii\helpers\ArrayHelper;

    class FilterHelper extends Object
    {

        public static $optionsList = [];

        /**
         * Get TaxGroups
         *
         * @return array
         */
        public static function optionsTemplate()
        {
            if (empty( static::$optionsList )) {
                return static::$optionsList = ArrayHelper::getColumn(
                    TaxGroup::find()
                            ->joinWith('lang')
                            ->where([ 'is_filter' => 'TRUE' ])
                            ->all(),
                    'lang.alias'
                );
            } else {
                return static::$optionsList;
            }

        }

        /**
         * Return custom filter-option link
         *
         * @param array  $filter
         * @param string $key
         * @param mixed  $value
         * @param bool   $remove
         *
         * @return array
         */
        public static function getFilterForOption(array $filter, string $key, $value, bool $remove = false)
        {

            $optionsTemplate = self::optionsTemplate();
            array_unshift($optionsTemplate, "special", "brands");

            $result = $filter;

            if (is_array($value)) {
                foreach ($value as $value_key => $value_items) {
                    if (!is_array($value_items)) {
                        $value_items = [ $value_items ];
                    }
                    foreach ($value_items as $value_item) {
                        if ($remove && isset( $result[ $key ] ) && ( $i = array_search(
                                $value_item,
                                $result[ $key ][ $value_key ]
                            ) ) !== false
                        ) {
                            unset( $result[ $key ][ $value_key ][ $i ] );
                            if (empty( $result[ $key ][ $value_key ] )) {
                                unset( $result[ $key ][ $value_key ] );
                            }
                        } else {
                            if (!isset( $result[ $key ][ $value_key ] ) || array_search(
                                    $value_item,
                                    $result[ $key ][ $value_key ]
                                ) === false
                            ) {
                                $result[ $key ][ $value_key ][] = $value_item;
                            }
                        }
                    }
                }
            } else {
                if ($remove && isset( $result[ $key ] ) && ( $i = array_search($value, $result[ $key ]) ) !== false) {
                    unset( $result[ $key ][ $i ] );
                    if (empty( $result[ $key ] )) {
                        unset( $result[ $key ] );
                    }
                } else {
                    if (!isset( $result[ $key ] ) || array_search($value, $result[ $key ]) === false) {
                        $result[ $key ][] = $value;
                    }
                }
            }

            $filterView = [];

            foreach ($optionsTemplate as $optionKey) {
                if (isset( $result[ $optionKey ] )) {
                    $filterView[ $optionKey ] = $result[ $optionKey ];
                }

            }

            return $filterView;
        }



        /**
         * select options for product variants with selected category
         *
         * @param integer $categoryId
         * @param integer $langId
         * @return mixed
         */
        public static function getProductVariantOptions($categoryId,$langId){

            $cacheKey = [
                'OptionsForFilter',
                'categoryId' => $categoryId,
                'langId' =>$langId
            ];
            if (!$OptionsForFilter = \Yii::$app->cache->get($cacheKey)) {
                $OptionsForFilter =  ( new Query() )->distinct()->select('tax_group_lang.alias')
                    ->from('product_variant_option')
                    ->innerJoin(
                        'tax_option',
                        'product_variant_option.option_id = tax_option.id'
                    )
                    ->innerJoin(
                        'tax_group',
                        'tax_group.id = tax_option.tax_group_id'
                    )
                    ->innerJoin(
                        'tax_group_lang',
                        'tax_group_lang.tax_group_id = tax_group.id'
                    )
                    ->innerJoin(
                        'tax_group_to_category',
                        'tax_group_to_category.tax_group_id = tax_group.id'
                    )
                    ->where([
                        'tax_group_lang.language_id' => $langId,
                        'tax_group_to_category.category_id' => $categoryId,
                        'tax_group.is_filter' => true
                    ])->all();
                $OptionsForFilter = ArrayHelper::getColumn($OptionsForFilter,'alias');
                \Yii::$app->cache->set($cacheKey, $OptionsForFilter, 3600 * 24);
            }

            return $OptionsForFilter;
        }


        /**
         * Fill query with filter conditions
         *
         * @param ActiveQuery $query
         * @param array       $params
         * @param integer       $categoryId
         * @param integer       $langId
         */
        public static function setQueryParams(ActiveQuery $query, array $params, $categoryId, $langId)
        {
            $last_query = null;
            $productVariantOptions = self::getProductVariantOptions($categoryId,$langId);
            foreach ($params as $key => $param) {
                switch ($key) {
                    case 'special':
                        unset($params[$key]);
                        self::filterSpecial($param, $query);
                        break;
                    case 'brands':
                        unset($params[$key]);
                        self::filterBrands($param, $query);
                        break;
                    case 'keywords':
                        unset($params[$key]);
                        self::filterKeywords($param, $query);
                        break;
                    case 'prices':
                        unset($params[$key]);
                        self::filterPrices($param, $query);
                        break;

                }
            }
            if(!empty($params)){
                self::filterOptions($params, $productVariantOptions, $query,$langId);
            }

        }



        /**
         * Tax Option filter
         *
         * @param string[]           $params
         * @param string[]           $productVariantOptions
         * @param \yii\db\Query|null $query
         * @param integer            $langId
         * @param bool               $in_stock
         *
         * @return \yii\db\Query
         */
        private static function filterOptions(array $params,$productVariantOptions, Query &$query = null, $langId, bool $in_stock = true)
        {
            $variant_query = ( new Query() )->distinct()
                ->select('product_variant.product_id as products')
                ->from('product_variant');
            $variantSearch = false;
            foreach ($params as $key=>$param){
                if(in_array($key, $productVariantOptions)){
                    $variantSearch = true;
                    unset($params[$key]);
                    $product_variant_id = ( new Query() )->distinct()->select('product_variant_option.product_variant_id as id')
                        ->from('product_variant_option')
                        ->innerJoin(
                            'tax_option',
                            'tax_option.id = product_variant_option.option_id'
                        )
                        ->innerJoin(
                            'tax_option_lang',
                            'tax_option_lang.tax_option_id = tax_option.id'
                        )
                        ->andWhere([ 'tax_option_lang.alias' => $param ])
                        ->andWhere([ 'tax_option_lang.language_id' => $langId ]);
                    $variant_query->andWhere(['product_variant.id'=>$product_variant_id]);
                }

            }

            if($variantSearch){
                if($in_stock) {
                    $variant_query->andWhere(['!=', 'product_variant.stock', 0]);
                }


                $query->andWhere([ 'product.id' => $variant_query ]);

            }



            if(!empty($params)) {
                $product_query = ( new Query() )->distinct()
                    ->select('product_option.product_id as products')
                    ->from('product_option')
                    ->innerJoin(
                        'tax_option',
                        'tax_option.id = product_option.option_id'
                    )
                    ->innerJoin(
                        'tax_option_lang',
                        'tax_option_lang.tax_option_id = tax_option.id'
                    );
                foreach ($params as $key=>$param){
                    $product_query
                        ->andWhere([ 'tax_option_lang.alias' => $param ]);

                }
                $product_query->andWhere([ 'tax_option_lang.language_id' => $langId ]);
                $query->andWhere([ 'product.id' => $product_query ]);
            }


        }



        /**
         * Fill $query with special filters (used in Product)
         *
         * @param array               $params
         * @param \yii\db\ActiveQuery $query
         */
        private static function filterSpecial(array $params, ActiveQuery $query)
        {
            $conditions = [];
            /**
             * @var string $key
             */
            foreach ($params as $key => $param) {
                $conditions[] = [
                    '=',
                    Product::tableName() . '.' . $key,
                    $param,
                ];
            }
            /* If 2 or more special conditions get all that satisfy at least one of them. */
            if (count($conditions) > 1) {
                array_unshift($conditions, 'or');
            } else {
                $conditions = $conditions[ 0 ];
            }
            $query->andFilterWhere($conditions);
        }

        /**
         * Fill query with brands filter
         *
         * @param int[]               $param
         * @param \yii\db\ActiveQuery $query
         */
        private static function filterBrands(array $param, ActiveQuery $query)
        {
            $query->joinWith('brand');
            $query->andFilterWhere([ 'brand.alias' => $param ]);
        }

        /**
         * Fill query with keywords filter
         *
         * @param array               $params
         * @param \yii\db\ActiveQuery $query
         */
        private static function filterKeywords(array $params, ActiveQuery $query)
        {
            $conditions = [];
            if (!empty( $params )) {
                if (!is_array($params)) {
                    $params = [ $params ];
                }
                /**
                 * @var string $param Inputed keyword
                 */
                foreach ($params as $param) {

                    if(iconv_strlen($param) >= 3){
                        $conditions[] = [
                            'or',
                            [
                                'ilike',
                                ProductLang::tableName() . '.title',
                                $param,
                            ],
                            [
                                'ilike',
                                BrandLang::tableName() . '.title',
                                $param,
                            ],
                            [
                                'ilike',
                                CategoryLang::tableName() . '.title',
                                $param,
                            ],
                            [
                                'ilike',
                                ProductVariantLang::tableName() . '.title',
                                $param,
                            ],
                            [
                                'ilike',
                                ProductVariant::tableName() . '.sku',
                                $param,
                            ]

                        ];
                    }


                }
            }
            if (count($conditions) > 1) {
                array_unshift($conditions, 'or');
            } else {
                $conditions = $conditions[ 0 ];
            }
            $query->andFilterWhere($conditions);
        }

        /**
         * Fill query with price limits filter
         *
         * @param array               $params
         * @param \yii\db\ActiveQuery $query
         */
        private static function filterPrices(array $params, ActiveQuery $query)
        {
            $conditions = [];
            if (!empty( $params[ 'min' ] ) && $params[ 'min' ] > 0) {
                $conditions[] = [
                    '>=',
                    ProductVariant::tableName() . '.price',
                    $params[ 'min' ],
                ];
            }
            if (!empty( $params[ 'max' ] ) && $params[ 'max' ] > 0) {
                $conditions[] = [
                    '<=',
                    ProductVariant::tableName() . '.price',
                    $params[ 'max' ],
                ];
            }
            if (count($conditions) > 1) {
                array_unshift($conditions, 'and');
            } else {
                $conditions = $conditions[ 0 ];
            }
            $query->andFilterWhere($conditions);
        }

    }
    