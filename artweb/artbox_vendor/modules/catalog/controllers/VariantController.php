<?php
    
    namespace artweb\artbox\modules\catalog\controllers;
    
    use artweb\artbox\modules\catalog\models\Product;
    use artweb\artbox\modules\catalog\models\ProductImage;
    use artweb\artbox\modules\catalog\models\ProductStock;
    use artweb\artbox\modules\catalog\models\ProductVariant;
    use artweb\artbox\modules\catalog\models\ProductVariantSearch;
    use artweb\artbox\modules\catalog\models\Stock;
    use Yii;
    use yii\db\ActiveQuery;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    
    /**
     * VartiantController implements the CRUD actions for ProductVariant model.
     */
    class VariantController extends Controller
    {
        
        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                'verbs' => [
                    'class'   => VerbFilter::className(),
                    'actions' => [
                        'delete' => [ 'POST' ],
                    ],
                ],
            ];
        }
        
        /**
         * Lists all ProductVariant models.
         *
         * @param int $product_id
         *
         * @return mixed
         */
        public function actionIndex($product_id)
        {
            $product = $this->findProduct($product_id);
            $searchModel = new ProductVariantSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            /**
             * @var ActiveQuery $query
             */
            $query = $dataProvider->query;
            $query->with('image')
                  ->andWhere([ 'product_id' => $product->id ]);
            
            return $this->render(
                'index',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'product'      => $product,
                ]
            );
        }
        
        /**
         * Displays a single ProductVariant model.
         *
         * @param integer $id
         *
         * @return mixed
         */
        public function actionView($id)
        {
            $model = $this->findModel($id);
            $properties = $model->getProperties();
            return $this->render(
                'view',
                [
                    'model'      => $model,
                    'properties' => $properties,
                ]
            );
        }
        
        /**
         * Creates a new ProductVariant model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         *
         * @param int $product_id
         *
         * @return mixed
         */
        public function actionCreate($product_id)
        {
            $product = $this->findProduct($product_id);
            $model = new ProductVariant();
            $model->product_id = $product->id;
            $model->generateLangs();
            if ($model->load(Yii::$app->request->post())) {
                $model->loadLangs(\Yii::$app->request);
                if ($model->save() && $model->transactionStatus) {
                    $model->stock = $this->saveStocks($model, Yii::$app->request->post('ProductStock', []));
                    if ($model->save(true, [ 'stock' ]) && $model->transactionStatus) {
                        return $this->redirect(
                            [
                                'index',
                                'product_id' => $product->id,
                            ]
                        );
                    }
                }
            }
            $groups = $model->getTaxGroupsByLevel(1);
            return $this->render(
                'create',
                [
                    'model'      => $model,
                    'modelLangs' => $model->modelLangs,
                    'groups'     => $groups,
                    'stocks'     => [ new ProductStock() ],
                    'product'    => $product,
                ]
            );
        }
        
        /**
         * Updates an existing ProductVariant model.
         * If update is successful, the browser will be redirected to the 'view' page.
         *
         * @param integer $product_id
         * @param integer $id
         *
         * @return mixed
         */
        public function actionUpdate($product_id, $id)
        {
            $product = $this->findProduct($product_id);
            $model = $this->findModel($id);
            $model->generateLangs();
            if ($model->load(Yii::$app->request->post())) {
                $model->loadLangs(\Yii::$app->request);
                if ($model->save() && $model->transactionStatus) {
                    $model->stock = $this->saveStocks($model, Yii::$app->request->post('ProductStock', []));
                    if ($model->save(true, [ 'stock' ]) && $model->transactionStatus) {
                        return $this->redirect(
                            [
                                'index',
                                'product_id' => $product_id,
                            ]
                        );
                    }
                }
            }
            $groups = $model->getTaxGroupsByLevel(1);
            return $this->render(
                'update',
                [
                    'model'      => $model,
                    'modelLangs' => $model->modelLangs,
                    'groups'     => $groups,
                    'stocks'     => ( !empty( $model->variantStocks ) ) ? $model->variantStocks : [ new ProductStock ],
                    'product'    => $product,
                ]
            );
        }
        
        /**
         * Deletes an existing ProductVariant model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         *
         * @param integer $product_id
         * @param integer $id
         *
         * @return mixed
         */
        public function actionDelete($product_id, $id)
        {
            
            $this->findModel($id)
                 ->delete();
            
            return $this->redirect(
                [
                    'index',
                    'product_id' => $product_id,
                ]
            );
        }
        
        /**
         * Deletes an existing ProductImage model.
         *
         * @param $id
         */
        public function actionDeleteImage($id)
        {
            $image = ProductImage::findOne($id);
            
            if ($image) {
                $image->delete();
            }
            
            print '1';
            exit;
        }
        
        /**
         * Save ProductStocks for ProductVariant and return total count of products.
         *
         * @param \artweb\artbox\modules\catalog\models\ProductVariant $productVariant
         * @param array|null                                    $productStocks
         *
         * @return int
         */
        protected function saveStocks(ProductVariant $productVariant, array $productStocks = null)
        {
            $total_quantity = 0;
            if (!empty( $productStocks )) {
                $productVariant->unlinkAll('stocks', true);
                $sorted_array = [];
                foreach ($productStocks as $productStock) {
                    if (!empty( $productStock[ 'title' ] ) && !empty( $productStock[ 'quantity' ] )) {
                        if (!empty( $sorted_array[ $productStock[ 'title' ] ] )) {
                            $sorted_array[ $productStock[ 'title' ] ] += $productStock[ 'quantity' ];
                        } else {
                            $sorted_array[ $productStock[ 'title' ] ] = $productStock[ 'quantity' ];
                        }
                    }
                }
                $productStocks = $sorted_array;
                $stock_names = array_keys($productStocks);
                $stocks = Stock::find()
                               ->joinWith('lang')
                               ->where([ 'stock_lang.title' => $stock_names ])
                               ->indexBy(function($row) {
                                   /**
                                    * @var Stock $row
                                    */
                                   return $row->lang->title;
                               })
                               ->all();
                foreach ($productStocks as $stockName => $quantity) {
                    $quantity = (int) $quantity;
                    if (!array_key_exists($stockName, $stocks)) {
                        $stock = new Stock();
                        $stock->generateLangs();
                        foreach ($stock->modelLangs as $modelLang) {
                            $modelLang->title = $stockName;
                        }
                        if (!$stock->save() || !$stock->transactionStatus) {
                            continue;
                        }
                    } else {
                        $stock = $stocks[ $stockName ];
                    }
                    $psModel = new ProductStock(
                        [
                            'product_variant_id' => $productVariant->id,
                            'stock_id'           => $stock->id,
                            'quantity'           => $quantity,
                        ]
                    );
                    if ($psModel->save()) {
                        $total_quantity += $quantity;
                    }
                }
            } else {
                $productVariant->unlinkAll('stocks', true);
            }
            return $total_quantity;
        }
        
        /**
         * Finds the ProductVariant model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         *
         * @param integer $id
         *
         * @return ProductVariant the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if (( $model = ProductVariant::find()
                                         ->where([ 'id' => $id ])
                                         ->with('lang')
                                         ->one() ) !== null
            ) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        /**
         * @param int $product_id
         *
         * @return Product
         * @throws NotFoundHttpException
         */
        protected function findProduct($product_id)
        {
            if (( $model = Product::find()
                                  ->with('lang')
                                  ->where([ 'id' => $product_id ])
                                  ->one() ) !== null
            ) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }
