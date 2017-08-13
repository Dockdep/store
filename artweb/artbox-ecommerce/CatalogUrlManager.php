<?php
    
    namespace artweb\artbox\ecommerce;
    
    use artweb\artbox\ecommerce\models\Category;
    use artweb\artbox\ecommerce\models\Product;
    use artweb\artbox\ecommerce\models\ProductVariant;
    use yii\helpers\VarDumper;
    use yii\web\HttpException;
    use yii\web\UrlRuleInterface;
    
    class CatalogUrlManager implements UrlRuleInterface
    {
        public $route_map = [];
        /**
         * Parses the given request and returns the corresponding route and parameters.
         *
         * @param \yii\web\UrlManager $manager the URL manager
         * @param \yii\web\Request    $request the request component
         *
         * @throws HttpException
         * @return array|boolean the parsing result. The route and the parameters are returned as an array.
         * If false, it means this rule cannot be used to parse this path info.
         */
        public function parseRequest($manager, $request)
        {
            /**
             * Fast url fix.
             * Did not helped with catalog products
             */
//            if (!preg_match('@^\/(ru|ua).*@i', $request->url) && $request->url !== '/') {
//                throw new HttpException(404, 'Page not found');
//            }
            
            $pathInfo = $request->getPathInfo();
            $paths = explode('/', $pathInfo);
            
            if (!array_key_exists($paths[ 0 ], $this->route_map)) {
                return false;
            }
            
            $params = [];
            if ($paths[ 0 ] == 'catalog') {
                $route = 'catalog/category';
                
                // Category
                if (!empty( $paths[ 1 ] )) {
                    $category = Category::find()
                                        ->joinWith(['lang'])
                                        ->where(
                                            [
                                                'category_lang.alias' => $paths[ 1 ],
                                            ]
                                        )
                                        ->one();
                    if (empty( $category )) {
                        throw new HttpException(404, 'Page not found');
                    }
                    $params[ 'category' ] = $category;
                } else {
                    throw new HttpException(404, 'Page not found');
                }
                if (!empty( $paths[ 2 ] )) {
                    // Filter
                    
                    if (strpos($paths[ 2 ], 'filters:') === 0) {
                        if (!isset( $paths[ 3 ] )) {
                            $this->parseFilter($paths[ 2 ], $params);
                        } else {
                            throw new HttpException(404, 'Page not found');
                        }
                        
                    } else {
                        throw new HttpException(404, 'Page not found');
                    }
                }
            }
//            elseif ($paths[ 0 ] == 'product') {
//
//                if (!empty( $paths[ 3 ] )) {
//                    throw new HttpException(404, 'Page not found');
//                }
//                $product = Product::find()
//                                  ->joinWith('lang')
//                                  ->where([ 'product_lang.alias' => $paths[ 1 ] ])
//                                  ->one();
//                $variant = ProductVariant::find()
//                                         ->joinWith('lang')
//                                         ->where([ 'sku' => $paths[ 2 ] ])
//                                         ->one();
//
//                if (empty( $variant->id ) || empty( $product->id )) {
//                    throw new HttpException(404, 'Page not found');
//                }
//                $route = 'catalog/product';
//                $params = [
//                    'product' => $paths[1],
//                    'variant' => $variant,
//                ];
//            }
            
            return [
                $route,
                $params,
            ];
        }
        /**
         * Creates a URL according to the given route and parameters.
         *
         * @param \yii\web\UrlManager $manager the URL manager
         * @param string              $route   the route. It should not have slashes at the beginning or the end.
         * @param array               $params  the parameters
         *
         * @return string|boolean the created URL, or false if this rule cannot be used for creating this URL.
         */
        public function createUrl($manager, $route, $params)
        {
            
            
            if (!in_array($route, $this->route_map)) {
                return false;
            }
            
            switch ($route) {
                case 'catalog/category':
                    if (!empty( $params[ 'category' ] )) {
                        $category_alias = is_object(
                            $params[ 'category' ]
                        ) ? $params[ 'category' ]->lang->alias : strtolower(
                            $params[ 'category' ]
                        );
                        $url = 'catalog/' . $category_alias ;
                        unset( $params[ 'category' ] );
                    } else {
                        $url = 'catalog';
                    }
                    
                    $this->setFilterUrl($params, $url);
                    
                    foreach ($params as $key => $param) {
                        if (empty( $params[ $key ] )) {
                            unset( $params[ $key ] );
                        }
                    }
                    
                    if (!empty( $params ) && ( $query = http_build_query($params) ) !== '') {
                        $url .= '?' . $query;
                    }
                    
                    return $url;
                    break;
                
                case 'catalog/product':
                    
                    $product_alias = '';
                    $variant_sku = '';
                    
                    if (!empty( $params[ 'product' ] )) {
                        $product_alias = strtolower($params[ 'product' ]);
                        unset( $params[ 'product' ] );
                    }

                    if (!empty( $params[ 'variant' ] )) {
                        $variant_sku = strtolower($params[ 'variant' ]);
                        unset( $params[ 'variant' ] );
                    }

                    $url = 'product/' . $product_alias . '/' . $variant_sku;

                    if (!empty( $params ) && ( $query = http_build_query($params) ) !== '') {
                        $url .= '?' . $query;
                    }

                    return $url;
                    break;
                
            }
        }
        
        private function option_value_encode($value)
        {
            return str_replace(
                [
                    ',',
                    '/',
                ],
                [
                    '~',
                    '&s;',
                ],
                $value
            );
        }
        
        private function setFilterUrl(&$params, &$url)
        {
            $filter = [];
            if (!empty( $params[ 'filters' ] )) {
                foreach ($params[ 'filters' ] as $key => $values) {
                    switch ($key) {
                        case 'prices':
                            $filter[] = $key . '=' . implode(':', $values);
                            break;
                        
                        default:
                            foreach ($values as &$value) {
                                $value = $this->option_value_encode($value);
                                if (empty( $value )) {
                                    unset( $value );
                                }
                            }
                            $filter[] = $key . '=' . implode(',', $values);
                            break;
                    }
                }
                if (!empty( $filter )) {
                    $url .= '/filters:' . implode(';', $filter);
                }
                unset( $params[ 'filters' ] );
            }
        }
        
        private function parseFilter($paths, &$params)
        {
            $params[ 'filters' ] = [];
            $filter_str = substr($paths, 8);
            $filter_options = explode(';', $filter_str);
            foreach ($filter_options as $filter_option) {
                $filter_exp = explode('=', $filter_option);
                if (!empty( $filter_exp[ 1 ] )) {
                    list( $filter_key, $filter_option ) = explode('=', $filter_option);
                    if ($filter_key == 'prices') { // price-interval section
                        $prices = explode(':', $filter_option);
                        $params[ 'filters' ][ $filter_key ] = [
                            'min' => floatval($prices[ 0 ]),
                            'max' => floatval($prices[ 1 ]),
                        ];
                    } else { // brands and other sections
                        $params[ 'filters' ][ $filter_key ] = explode(',', $filter_option);
                    }
                } else {
                    throw new HttpException(404, 'Page not found');
                }
            }
        }
        
    }