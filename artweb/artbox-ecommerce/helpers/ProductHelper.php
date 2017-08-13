<?php
    
    namespace artweb\artbox\ecommerce\helpers;
    
    use artweb\artbox\ecommerce\models\Category;
    use artweb\artbox\ecommerce\models\Product;
    use artweb\artbox\ecommerce\models\ProductVariant;
    use yii\base\InvalidConfigException;
    use yii\base\InvalidParamException;
    use yii\base\Object;
    use Yii;
    use yii\db\ActiveQuery;
    use yii\helpers\ArrayHelper;
    
    class ProductHelper extends Object
    {
        
        /**
         * @todo ArtboxTree
         * @return array
         */
        public static function getCategories()
        {
            return Category::find()
                           ->getTree(null, 'lang');
        }
        
        /**
         * Add $product_id to last products in session.
         *
         * @param int $product_id
         */
        public static function addLastProducts(int $product_id)
        {
            $last_products = self::getLastProducts();
            if (!in_array($product_id, $last_products)) {
                $last_products[] = intval($product_id);
                Yii::$app->session->set('last_products', $last_products);
            }
        }
        
        /**
         * Get last products ids from session or last Product models with ProductVariant, which are in stock if
         * $as_object is true
         *
         * @param bool $as_object
         *
         * @return array
         */
        public static function getLastProducts(bool $as_object = false)
        {
            $last_products = Yii::$app->session->get('last_products', []);
            if ($as_object) {
                $last_products = Product::find()
                                        ->innerJoinWith(
                                            [
                                                'enabledVariants' => function ($query) {
                                                    /**
                                                     * @var ActiveQuery $query
                                                     */
                                                    $query->joinWith('lang')
                                                        ->with('images');
                                                },
                                            ]
                                        )
                                        ->where([ 'product.id' => $last_products ])
                                        ->andWhere(
                                            [
                                                '!=',
                                                'product_variant.stock',
                                                0,
                                            ]
                                        )
                                        ->indexBy('id')
                                        ->all();
            }
            return array_reverse($last_products);
        }


        public static function trueWordForm($num, $form_for_1, $form_for_2, $form_for_5){
            $num = abs($num) % 100;
            $num_x = $num % 10;
            if ($num > 10 && $num < 20)
                return $form_for_5;
            if ($num_x > 1 && $num_x < 5)
                return $form_for_2;
            if ($num_x == 1)
                return $form_for_1;
            return $form_for_5;
        }
        
        /**
         * Get special Products array with ProductVariants, which are in stock
         * Available types:
         * * top
         * * new
         * * promo
         *
         * @param string $type
         * @param int    $count
         *
         * @return Product[]
         */
        public static function getSpecialProducts(string $type, int $count)
        {
            switch ($type) {
                case 'top':
                    $data = [ 'is_top' => true ];
                    break;
                case 'new':
                    $data = [ 'is_new' => true ];
                    break;
                case 'promo':
                    $data = [ 'is_discount' => true ];
                    break;
                default:
                    return [];
                    break;
            }
            return Product::find()
                          ->with('lang')
                          ->joinWith('variants.lang')
                          ->where($data)
                          ->andWhere(
                              [
                                  '!=',
                                  'productVariant.stock',
                                  0,
                              ]
                          )
                          ->limit($count)
                          ->all();
        }
        
        /**
         * Get ActiveQuery to get similar products to $product
         *
         * @param Product $product
         * @param int     $count
         *
         * @return ActiveQuery
         */
        public static function getSimilarProducts(Product $product, $count = 10): ActiveQuery
        {
            $query = Product::find();
            if (empty( $product->properties )) {
                $query->where('0 = 1');
                return $query;
            }
            $query->innerJoinWith('variants')
                  ->joinWith('categories')
                  ->where(
                      [
                          '!=',
                          'product_variant.stock',
                          0,
                      ]
                  )
                  ->andWhere(
                      [ 'product_category.category_id' => ArrayHelper::getColumn($product->categories, 'id') ]
                  );
            $options = [];
            foreach ($product->properties as $group) {
                foreach ($group->options as $option) {
                    $options[] = $option->id;
                }
            }
            if (!empty( $options )) {
                $query->innerJoinWith('options')
                      ->andWhere([ 'product_option.option_id' => $options ]);
            } else {
                $query->where('0 = 1');
                return $query;
            }
            $query->andWhere(
                [
                    '!=',
                    'product.id',
                    $product->id,
                ]
            );


            $query->andWhere([
                '>=',
                ProductVariant::tableName() . '.price',
                ($product->variant->price * 0.8),
            ]);


            $query->andWhere([
                '<=',
                ProductVariant::tableName() . '.price',
                ($product->variant->price * 1.2),
            ]);



            $query->groupBy('product.id');
            $query->limit($count);
            return $query;
        }
        
        /**
         * Add last category id to session
         *
         * @param int $category_id
         */
        public static function addLastCategory(int $category_id)
        {
            \Yii::$app->session->set('last_category_id', $category_id);
        }
        
        /**
         * Get last category id from session
         *
         * @return int
         */
        public static function getLastCategory()
        {
            return \Yii::$app->session->get('last_category_id');
        }
        
        /**
         * Group Product[] by Categories
         * Array of categories with 2 levels:
         * <code>
         * [
         *  'parent_id' => [
         *      'id',
         *      'name',
         *      'count',
         *      'children' => [
         *          'id' => [
         *              'id',
         *              'name',
         *              'count',
         *          ]
         *      ]
         *  ]
         * ]
         * </code>
         *
         * @param Product[] $products
         *
         * @return array
         * @throws InvalidConfigException
         */
        public static function groupByCategories(array $products): array
        {
            $categoryList = [];
            foreach ($products as $product) {
                if (!( $product instanceof Product )) {
                    throw new InvalidParamException('$products must be array of ' . Product::className());
                }
                foreach ($product->categories as $category) {
                    /**
                     * @var Category|null $parentCategory
                     */
                    $parentCategory = $category->parentAR;
                    /* If category has parent add current category to parents children array,
                    else create current category as root category */
                    if ($parentCategory) {
                        /* If parent category already in $categoryList search current category in its array,
                        else create it in $categoryList and add current category to it */
                        if (array_key_exists($parentCategory->id, $categoryList)) {
                            /* If current category already in parent category array increament count by 1,
                            else add current category to parent category children array */
                            if (array_key_exists($category->id, $categoryList[ $parentCategory->id ][ 'children' ])) {
                                $categoryList[ $parentCategory->id ][ 'children' ][ $category->id ][ 'count' ] += 1;
                            } else {
                                $categoryList[ $parentCategory->id ][ 'children' ][ $category->id ] = [
                                    'id'    => $category->id,
                                    'name'  => $category->lang->title,
                                    'alias' => $category->lang->alias,
                                    'count' => 1,
                                ];
                            }
                        } else {
                            $categoryList[ $parentCategory->id ] = [
                                'id'       => $parentCategory->id,
                                'name'     => $parentCategory->lang->title,
                                'alias'    => $parentCategory->lang->alias,
                                'children' => [
                                    $category->id => [
                                        'id'    => $category->id,
                                        'name'  => $category->lang->title,
                                        'alias' => $category->lang->alias,
                                        'count' => 1,
                                    ],
                                ],
                                'count'    => 0,
                            ];
                        }
                    } else {
                        /* If current category already in $categoryList increment its count by 1,
                        else add it to $categoryList */
                        if (array_key_exists($category->id, $categoryList)) {
                            $categoryList[ $category->id ][ 'count' ] += 1;
                        } else {
                            $categoryList[ $category->id ] = [
                                'id'       => $category->id,
                                'name'     => $category->lang->title,
                                'alias'    => $category->lang->alias,
                                'count'    => 1,
                                'children' => [],
                            ];
                        }
                    }
                }
            }
            return $categoryList;
        }
    }