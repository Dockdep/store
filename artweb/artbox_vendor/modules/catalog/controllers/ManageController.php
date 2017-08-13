<?php
    
    namespace artweb\artbox\modules\catalog\controllers;
    
    use artweb\artbox\modules\language\models\Language;
    use artweb\artbox\modules\catalog\models\Export;
    use artweb\artbox\modules\catalog\models\Import;
    use artweb\artbox\modules\catalog\models\ProductImage;
    use Yii;
    use artweb\artbox\modules\catalog\models\Product;
    use artweb\artbox\modules\catalog\models\ProductSearch;
    use yii\db\ActiveQuery;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use yii\web\Response;
    use yii\web\UploadedFile;
    
    /**
     * ManageController implements the CRUD actions for Product model.
     */
    class ManageController extends Controller
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
         * Lists all Product models.
         *
         * @return mixed
         */
        public function actionIndex()
        {
            $searchModel = new ProductSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            
            return $this->render(
                'index',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                ]
            );
        }
        
        /**
         * Displays a single Product model.
         *
         * @param integer $id
         *
         * @return mixed
         */
        public function actionView($id)
        {
            $model = $this->findModel($id);
            $categories = $model->getCategories()
                                ->with('lang')
                                ->all();
            $variants = $model->getVariants()
                              ->with('lang')
                              ->all();
            $properties = $model->getProperties();
            return $this->render(
                'view',
                [
                    'model'      => $this->findModel($id),
                    'categories' => $categories,
                    'variants'   => $variants,
                    'properties' => $properties,
                ]
            );
        }
        
        /**
         * Creates a new Product model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         *
         * @return mixed
         */
        public function actionCreate()
        {
            $model = new Product();
            $model->generateLangs();
            if ($model->load(Yii::$app->request->post())) {
                $model->loadLangs(\Yii::$app->request);
                if ($model->save() && $model->transactionStatus) {
                    return $this->redirect(
                        [
                            'view',
                            'id' => $model->id,
                        ]
                    );
                }
            }
            return $this->render(
                'create',
                [
                    'model'      => $model,
                    'modelLangs' => $model->modelLangs,
                ]
            );
        }
        
        /**
         * Updates an existing Product model.
         * If update is successful, the browser will be redirected to the 'view' page.
         *
         * @param integer $id
         *
         * @return mixed
         */
        public function actionUpdate($id)
        {
            $model = $this->findModel($id);
            $model->generateLangs();
            if ($model->load(Yii::$app->request->post())) {
                $model->loadLangs(\Yii::$app->request);
                if ($model->save() && $model->transactionStatus) {
                    return $this->redirect(
                        [
                            'view',
                            'id' => $model->id,
                        ]
                    );
                }
            }
            /**
             * @var ActiveQuery $groups
             */
            $groups = $model->getTaxGroupsByLevel(0);
            return $this->render(
                'update',
                [
                    'model'      => $model,
                    'modelLangs' => $model->modelLangs,
                    'groups'     => $groups,
                ]
            );
        }
        
        /**
         * Deletes an existing Product model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         *
         * @param integer $id
         *
         * @return mixed
         */
        public function actionDelete($id)
        {
            $this->findModel($id)
                 ->delete();
            return $this->redirect([ 'index' ]);
        }
        
        /**
         * Deletes an existing ProductImage model.
         *
         * @param int $id
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
         * Toggle product top status
         *
         * @param int $id Product ID
         *
         * @return \yii\web\Response
         */
        public function actionIsTop($id)
        {
            $model = $this->findModel($id);
            
            $model->is_top = intval(empty( $model->is_top ));
            
            $model->save(false, [ 'is_top' ]);
            
            return $this->redirect([ 'index' ]);
        }
        
        /**
         * Toggle product new status
         *
         * @param int $id Product ID
         *
         * @return \yii\web\Response
         */
        public function actionIsNew($id)
        {
            $model = $this->findModel($id);
            
            $model->is_new = intval(empty( $model->is_new ));
            
            $model->save(false, [ 'is_new' ]);
            
            return $this->redirect([ 'index' ]);
        }
        
        /**
         * Toggle product discount status
         *
         * @param int $id Product ID
         *
         * @return \yii\web\Response
         */
        public function actionIsDiscount($id)
        {
            $model = $this->findModel($id);
            
            $model->is_discount = intval(empty( $model->is_discount ));
            
            $model->save(false, [ 'is_discount' ]);
            
            return $this->redirect([ 'index' ]);
        }
        
        /**
         * Perform product import
         *
         * @return string
         */
        public function actionImport()
        {
            $model = new Import();
            
            $languages = Language::find()
                                 ->select(
                                     [
                                         'name',
                                         'id',
                                     ]
                                 )
                                 ->where([ 'status' => 1 ])
                                 ->orderBy([ 'default' => SORT_DESC ])
                                 ->asArray()
                                 ->indexBy('id')
                                 ->column();
            
            if ($model->load(Yii::$app->request->post())) {
                \Yii::$app->session->set('export_lang', $model->lang);
                $file = UploadedFile::getInstances($model, 'file');
                $method = 'go' . ucfirst($model->type);
                $target = Yii::getAlias('@uploadDir') . '/' . Yii::getAlias('@uploadFile' . ucfirst($model->type));
                if (empty( $file )) {
                    $model->errors[] = 'File not upload';
                } elseif ($method == 'goPrices' && $file[ 0 ]->name != 'file_1.csv') {
                    $model->errors[] = 'File need "file_1.csv"';
                } elseif ($method == 'goProducts' && $file[ 0 ]->name == 'file_1.csv') {
                    $model->errors[] = 'File can not "file_1.csv"';
                } elseif ($model->validate() && $file[ 0 ]->saveAs($target)) {
                    // PROCESS PAGE
                    return $this->render(
                        'import-process',
                        [
                            'model'  => $model,
                            'method' => $model->type,
                            'target' => $target,
                        ]
                    );
                } else {
                    $model->errors[] = 'File can not be upload or other error';
                }
            }
            
            return $this->render(
                'import',
                [
                    'model'     => $model,
                    'languages' => $languages,
                ]
            );
        }
        
        /**
         * Import products via AJAX
         *
         * @return array
         * @throws \HttpRequestException
         */
        public function actionProducts()
        {
            $from = Yii::$app->request->get('from', 0);
            
            $model = new Import();
            
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $model->goProducts($from, 1);
            } else {
                throw new \HttpRequestException('Must be AJAX');
            }
        }
        
        /**
         * Import prices via AJAX
         *
         * @return array
         * @throws \HttpRequestException
         */
        public function actionPrices()
        {
            $from = Yii::$app->request->get('from', 0);
            
            $model = new Import();
            
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $model->goPrices($from, 10);
            } else {
                throw new \HttpRequestException('Must be AJAX');
            }
        }
        
        /**
         * Export proccess via AJAX
         *
         * @param int    $from
         * @param string $filename
         *
         * @return array
         * @throws \HttpRequestException
         */
        public function actionExportProcess($from, $filename)
        {
            
            $model = new Export();
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $model->process($filename, $from);
            } else {
                throw new \HttpRequestException('Must be AJAX');
            }
        }
    
        /**
         * Perform export
         *
         * @return string
         */
        public function actionExport()
        {
            $model = new Export();
            
            if ($model->load(Yii::$app->request->post())) {
                \Yii::$app->session->set('export_lang', $model->lang);
                return $this->render(
                    'export-process',
                    [
                        'model'  => $model,
                        'method' => 'export',
                    ]
                );
            }
            
            return $this->render(
                'export',
                [
                    'model' => $model,
                ]
            );
        }
        
        /**
         * Finds the Product model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         *
         * @param integer $id
         *
         * @return Product the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if (( $model = Product::find()
                                  ->where([ 'id' => $id ])
                                  ->with('lang')
                                  ->one() ) !== null
            ) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }
