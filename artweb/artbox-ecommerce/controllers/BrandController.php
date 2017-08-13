<?php
    
    namespace artweb\artbox\ecommerce\controllers;
    
    use artweb\artbox\ecommerce\models\BrandSize;
    use Yii;
    use artweb\artbox\ecommerce\models\Brand;
    use artweb\artbox\ecommerce\models\BrandSearch;
    use yii\data\ActiveDataProvider;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use yii\filters\AccessControl;
    
    /**
     * BrandController implements the CRUD actions for Brand model.
     */
    class BrandController extends Controller
    {
        
        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
//                'access' => [
//                    'class' => AccessControl::className(),
//                    'rules' => [
//                        [
//                            'actions' => [
//                                'login',
//                                'error',
//                            ],
//                            'allow'   => true,
//                        ],
//                        [
//                            'actions' => [
//                                'logout',
//                                'index',
//                                'create',
//                                'update',
//                                'view',
//                                'delete',
//                                'delete-image',
//                                'size',
//                            ],
//                            'allow'   => true,
//                            'roles'   => [ '@' ],
//                        ],
//                    ],
//                ],
                'verbs'  => [
                    'class'   => VerbFilter::className(),
                    'actions' => [
                        'logout'       => [ 'post' ],
                        'delete-image' => [ 'post' ],
                    ],
                ],
            ];
        }
        
        /**
         * Lists all Brand models.
         *
         * @return mixed
         */
        public function actionIndex()
        {
            $searchModel = new BrandSearch();
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
         * Displays a single Brand model.
         *
         * @param integer $id
         *
         * @return mixed
         */
        public function actionView($id)
        {
            return $this->render(
                'view',
                [
                    'model' => $this->findModel($id),
                ]
            );
        }
        
        /**
         * Creates a new Brand model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         *
         * @return mixed
         */
        public function actionCreate()
        {
            $model = new Brand();
            $model->generateLangs();
            if ($model->load(Yii::$app->request->post())) {
                $model->loadLangs(\Yii::$app->request);
                if ($model->save() && $model->transactionStatus) {
                    return is_null(Yii::$app->request->post('create_and_new')) ? $this->redirect(
                        [
                            'view',
                            'id' => $model->id,
                        ]
                    ) : $this->redirect(array_merge([ 'create' ], Yii::$app->request->queryParams));
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
         * Updates an existing Brand model.
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
            return $this->render(
                'update',
                [
                    'model'      => $model,
                    'modelLangs' => $model->modelLangs,
                ]
            );
        }
        
        /**
         * Deletes an existing Brand model.
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
        
        public function actionSize($id)
        {
            $model = $this->findModel($id);
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => BrandSize::find()
                                        ->where(
                                            [
                                                'brand_id' => $id,
                                            ]
                                        ),
                ]
            );
            
            return $this->render(
                'size',
                [
                    'model'        => $model,
                    'dataProvider' => $dataProvider,
                ]
            );
        }
        
        public function actionDeleteImage($id)
        {
            $model = $this->findModel($id);
            $model->image = NULL;
            $model->updateAttributes([ 'image' ]);
            return true;
        }
        
        /**
         * Finds the Brand model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         *
         * @param integer $id
         *
         * @return Brand the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if (( $model = Brand::find()
                                ->with('lang')
                                ->where([ 'id' => $id ])
                                ->one() ) !== NULL
            ) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }
