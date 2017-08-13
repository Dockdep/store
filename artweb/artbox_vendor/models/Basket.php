<?php
    
    namespace artweb\artbox\models;
    
    use artweb\artbox\modules\catalog\models\ProductVariant;
    use yii\base\Component;
    use yii\web\NotFoundHttpException;
    
    /**
     * Class Basket to work with basket
     *
     * @package artweb\artbox\models
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
            }
            parent::__construct($config);
        }
        
        /**
         * Increment product variant with $product_variant_id count by 1
         *
         * @param int $product_variant_id
         */
        public function add(int $product_variant_id)
        {
            $data = $this->getData();
            if (array_key_exists($product_variant_id, $data)) {
                if ($data[ $product_variant_id ][ 'count' ] <= 0) {
                    $data[ $product_variant_id ] = 1;
                } else {
                    $data[ $product_variant_id ][ 'count' ] += 1;
                }
            } else {
                if ($this->findModel($product_variant_id)) {
                    $data[ $product_variant_id ] = [
                        'count' => 1,
                    ];
                }
            }
            $this->setData($data);
        }
        
        /**
         * Set product variant with $product_variant_id to $count
         *
         * @param int $product_variant_id
         * @param int $count
         */
        private function set(int $product_variant_id, int $count)
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
         * Find Product Variant by $product_variant_id
         *
         * @param int $product_variant_id
         *
         * @return \artweb\artbox\modules\catalog\models\ProductVariant
         * @throws \yii\web\NotFoundHttpException
         */
        public function findModel(int $product_variant_id): ProductVariant
        {
            /**
             * @var ProductVariant $model
             */
            $model = ProductVariant::find()
                                   ->where([ 'product_variant.id' => $product_variant_id ])
                                   ->joinWith('lang', true, 'INNER JOIN')
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
            return ProductVariant::find()
                                 ->where([ 'product_variant.id' => $product_variant_ids ])
                                 ->joinWith('lang', true, 'INNER JOIN')
                                 ->with(
                                     [
                                         'product',
                                         'image',
                                     ]
                                 )
                                 ->all();
        }
        
        /**
         * Clear basket
         */
        public function clear()
        {
            $this->setData([]);
        }
        
    }
    