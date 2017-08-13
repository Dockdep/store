<?php
    
    namespace artweb\artbox\modules\catalog\helpers;
    
    use artweb\artbox\modules\catalog\models\Category;
    use artweb\artbox\modules\catalog\models\Product;
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
         * Add $product_id to last products in session. Limit 16 products.
         *
         * @param int $product_id
         */
        public static function addLastProducts(int $product_id)
        {
            $last_products = self::getLastProducts();
            if (!in_array($product_id, $last_products)) {
                $last_products[] = intval($product_id);
                if (count($last_products) > 16) {
                    array_shift($last_products);
                }
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
                                        ->joinWith([ 'variant' ])
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
        public static function getLastCategory(): int
        {
            return \Yii::$app->session->get('last_category_id');
        }
    }