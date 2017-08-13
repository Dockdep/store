<?php
    
    namespace artweb\artbox\ecommerce\controllers;
    
    use artweb\artbox\ecommerce\models\ProductVideo;
    use artweb\artbox\language\models\Language;
    use artweb\artbox\ecommerce\models\Export;
    use artweb\artbox\ecommerce\models\Import;
    use artweb\artbox\ecommerce\models\ProductImage;
    use Yii;
    use artweb\artbox\ecommerce\models\Product;
    use artweb\artbox\ecommerce\models\ProductSearch;
    use yii\db\ActiveQuery;
    use yii\helpers\Html;
    use yii\helpers\VarDumper;
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
                    if (!empty( \Yii::$app->request->post('ProductVideo') ))
                    {
                        foreach (\Yii::$app->request->post('ProductVideo') as $video)
                        {
                            $modelVideo = new ProductVideo();
                            $modelVideo->url = $video['url'];
                            $modelVideo->title = $video['title'];
                            $model->link('videos', $modelVideo);
                        }
                    }
                    
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
                    'videos' => !empty( $model->videos ) ? $model->videos : [ new ProductVideo() ],
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
                $model->unlinkAll('videos', true);
                if ($model->save() && $model->transactionStatus) {
                    if (!empty( \Yii::$app->request->post('ProductVideo') ))
                    {
                        foreach (\Yii::$app->request->post('ProductVideo') as $video)
                        {
                            $modelVideo = new ProductVideo();
                            $modelVideo->url = $video['url'];
                            $modelVideo->title = $video['title'];
                            $model->link('videos', $modelVideo);
                        }
                    }
                    
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
                    'videos'     => !empty( $model->videos ) ? $model->videos : [ new ProductVideo() ],
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
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $product = Product::findOne($id);
            $product->is_top = !$product->is_top;
            if ($product->save()) {
                if ($product->is_top) {
                    $tag = Html::tag('span', '', [
                        'class' => 'glyphicon glyphicon-star',
                    ]);
                } else {
                    $tag = Html::tag('span', '', [
                        'class' => 'glyphicon glyphicon-star-empty',
                    ]);
                }
                return [
                    'success' => true,
                    'tag' => $tag,
                    'message' => 'Статус ТОП успешно изменен',
                ];
            } else {
                return [];
            }
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
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $product = Product::findOne($id);
            $product->is_new = !$product->is_new;
            if ($product->save()) {
                if ($product->is_new) {
                    $tag = Html::tag('span', '', [
                        'class' => 'glyphicon glyphicon-heart',
                    ]);
                } else {
                    $tag = Html::tag('span', '', [
                        'class' => 'glyphicon glyphicon-heart-empty',
                    ]);
                }
                return [
                    'success' => true,
                    'tag' => $tag,
                    'message' => 'Статус НОВЫЙ успешно изменен',
                ];
            } else {
                return [];
            }
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
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $product = Product::findOne($id);
            $product->is_discount = !$product->is_discount;
            if ($product->save()) {
                if ($product->is_discount) {
                    $tag = Html::tag('span', '', [
                        'class' => 'glyphicon glyphicon-tags',
                    ]);
                } else {
                    $tag = Html::tag('span', '', [
                        'class' => 'glyphicon glyphicon-tag',
                    ]);
                }
                return [
                    'success' => true,
                    'tag' => $tag,
                    'message' => 'Статус АКЦИОННЫЙ успешно изменен',
                ];
            } else {
                return [];
            }
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
                return $model->goProducts($from, 10);
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
    
        public function actionDeleteSize($id)
        {
            $model = $this->findModel($id);
            $model->size_image = null;
            $model->updateAttributes(['size_image']);
            return true;
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
                                  ->one() ) !== NULL
            ) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }
