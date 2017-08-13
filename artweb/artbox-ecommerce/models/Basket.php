<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use yii\base\Component;
    use yii\helpers\Json;
    use yii\web\Cookie;
    use yii\web\NotFoundHttpException;
    
    /**
     * Class Basket to work with basket
     *
     * @property bool isCredit
     * @package artweb\artbox\ecommerce\models
     */
    class Basket extends Component
    {
        /**
         * Session object
         *
         * @var \yii\web\Session
         */
        public $session;
        
        /**
         * Basket constructor.
         * Check for basket variable in session and set it to empty array if not exist.
         *
         * @param array $config
         */
        public function __construct(array $config = [])
        {
            $this->session = \Yii::$app->session;
            if (!$this->session->has('basket')) {
                $this->session->set('basket', []);
            } elseif (!empty( $this->session->get('basket') )) {
                $cookies = \Yii::$app->response->cookies;
                $cookies->add(
                    new Cookie(
                        [
                            'name'  => 'basket',
                            'value' => Json::encode($this->session->get('basket')),
                        ]
                    )
                );
            }
            parent::__construct($config);
        }
        
        /**
         * Increment product variant with $product_variant_id count by 1
         *
         * @param int $product_variant_id
         * @param int $count
         */
        public function add(int $product_variant_id, int $count)
        {
            $data = $this->getData();
            if (array_key_exists($product_variant_id, $data)) {
                if ($data[ $product_variant_id ][ 'count' ] <= 0) {
                    $data[ $product_variant_id ][ 'count' ] = $count;
                } else {
                    $data[ $product_variant_id ][ 'count' ] += $count;
                }
            } else {
                if ($this->findModel($product_variant_id)) {
                    $data[ $product_variant_id ] = [
                        'count' => $count,
                    ];
                }
            }
            if ($data[ $product_variant_id ][ 'count' ] <= 0) {
                unset( $data[ $product_variant_id ] );
            }
            $this->setData($data);
        }
        
        /**
         * Set product variant with $product_variant_id to $count
         *
         * @param int $product_variant_id
         * @param int $count
         */
        public function set(int $product_variant_id, int $count)
        {
            $data = $this->getData();
            if (array_key_exists($product_variant_id, $data)) {
                $data[ $product_variant_id ][ 'count' ] = $count;
                if ($data[ $product_variant_id ][ 'count' ] <= 0) {
                    unset( $data[ $product_variant_id ] );
                }
            } elseif ($count > 0) {
                if ($this->findModel($product_variant_id)) {
                    $data[ $product_variant_id ] = [
                        'count' => $count,
                    ];
                }
            }
            $this->setData($data);
        }
        
        /**
         * Delete product variant with $product_variant_id from basket
         *
         * @param int $product_variant_id
         */
        public function delete(int $product_variant_id)
        {
            $this->set($product_variant_id, 0);
        }
        
        /**
         * Get basket data
         *
         * @return array
         */
        public function getData(): array
        {
            return $this->session->get('basket');
        }
        
        /**
         * Get basket item with $product_variant_id. Returns false if item not in basket.
         *
         * @param int $product_variant_id
         *
         * @return bool|array
         */
        public function getItem(int $product_variant_id)
        {
            $data = $this->getData();
            if (!empty( $data[ $product_variant_id ] )) {
                return $data[ $product_variant_id ];
            } else {
                return false;
            }
        }
        
        /**
         * Set basket data
         *
         * @param array $data
         */
        public function setData(array $data)
        {
            $this->session->set('basket', $data);
            $cookies = \Yii::$app->response->cookies;
            if (empty( $data )) {
                $cookies->remove('basket');
                $cookies->remove('isCredit');
            } else {
                $cookies->add(
                    new Cookie(
                        [
                            'name'  => 'basket',
                            'value' => Json::encode($data),
                        ]
                    )
                );
            }
        }
        
        /**
         * Get count of product variants in basket
         *
         * @return int
         */
        public function getCount(): int
        {
            $data = $this->getData();
            return count($data);
        }
        
        /**
         * Get sum of product variants in basket
         *
         * @return float
         */
        public function getSum(): float
        {
            $data = $this->getData();
            $models = $this->findModels(array_keys($data));
            $sum = 0;
            foreach ($models as $model) {
                $sum += $model->price * $data[ $model->id ][ 'count' ];
            }
            return $sum;
        }
        
        /**
         * Find Product Variant by $product_variant_id
         *
         * @param int $product_variant_id
         *
         * @return \artweb\artbox\ecommerce\models\ProductVariant
         * @throws \yii\web\NotFoundHttpException
         */
        public function findModel(int $product_variant_id): ProductVariant
        {
            /**
             * @var ProductVariant $model
             */
            $model = ProductVariant::find()
                                   ->where([ 'product_variant.id' => $product_variant_id ])
                                   ->andWhere(
                                       [
                                           '>',
                                           'product_variant.stock',
                                           0,
                                       ]
                                   )
                                   ->joinWith('lang')
                                   ->one();
            if (empty( $model )) {
                throw new NotFoundHttpException(\Yii::t('app', 'Product not found'));
            } else {
                return $model;
            }
        }
        
        /**
         * Find all Product Variants filtered by $product_variant_ids
         *
         * @param array $product_variant_ids
         *
         * @return ProductVariant[]
         */
        public function findModels(array $product_variant_ids)
        {
            if (empty( $product_variant_ids )) {
                return [];
            }
            return ProductVariant::find()
                                 ->where([ 'product_variant.id' => $product_variant_ids ])
                                 ->joinWith('lang', 'product')
                                 ->with(
                                     [
                                         'product.lang',
                                         'image',
                                     ]
                                 )
                                 ->indexBy('id')
                                 ->all();
        }
        
        /**
         * Clear basket
         */
        public function clear()
        {
            $this->setData([]);
            \Yii::$app->response->cookies->remove('isCredit');
        }
        
        /**
         * Check if is credit cookie flag set
         *
         * @return bool
         */
        public static function getIsCredit(): bool
        {
            // Get cookies from global in order to skip yii2 cookie validation
            $cookies = $_COOKIE;
            if (isset( $cookies[ 'isCredit' ] )) {
                return true;
            } else {
                return false;
            }
        }
        
    }
    