<?php
    
    namespace artweb\artbox\modules\catalog\controllers;
    
    use Yii;
    use artweb\artbox\modules\catalog\models\ProductUnit;
    use artweb\artbox\modules\catalog\models\ProductUnitSearch;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    
    /**
     * ProductUnitController implements the CRUD actions for ProductUnit model.
     */
    class ProductUnitController extends Controller
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
         * Lists all ProductUnit models.
         *
         * @return mixed
         */
        public function actionIndex()
        {
            $searchModel = new ProductUnitSearch();
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
         * Displays a single ProductUnit model.
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
         * Creates a new ProductUnit model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         *
         * @return mixed
         */
        public function actionCreate()
        {
            $model = new ProductUnit();
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
         * Updates an existing ProductUnit model.
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
         * Deletes an existing ProductUnit model.
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
         * Finds the ProductUnit model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         *
         * @param integer $id
         *
         * @return ProductUnit the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if (( $model = ProductUnit::find()
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
