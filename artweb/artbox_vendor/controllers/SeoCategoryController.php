<?php
    
    namespace artweb\artbox\controllers;
    
    use Yii;
    use artweb\artbox\models\SeoCategory;
    use artweb\artbox\models\SeoCategorySearch;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use developeruz\db_rbac\behaviors\AccessBehavior;
    
    /**
     * SeoCategoryController implements the CRUD actions for SeoCategory model.
     */
    class SeoCategoryController extends Controller
    {
        
        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                'access' => [
                    'class' => AccessBehavior::className(),
                    'rules' => [
                        'site' => [
                            [
                                'actions' => [
                                    'login',
                                    'error',
                                ],
                                'allow'   => true,
                            ],
                        ],
                    ],
                ],
                'verbs'  => [
                    'class' => VerbFilter::className(),
                
                ],
            ];
        }
        
        /**
         * Lists all SeoCategory models.
         *
         * @return mixed
         */
        public function actionIndex()
        {
            $searchModel = new SeoCategorySearch();
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
         * Creates a new SeoCategory model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         *
         * @return mixed
         */
        public function actionCreate()
        {
            $model = new SeoCategory();
            $model->generateLangs();
            
            if ($model->load(Yii::$app->request->post())) {
                $model->loadLangs(\Yii::$app->request);
                if ($model->save() && $model->transactionStatus) {
                    return $this->redirect(
                        [
                            'index',
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
         * Updates an existing SeoCategory model.
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
         * Deletes an existing SeoCategory model.
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
         * Finds the SeoCategory model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         *
         * @param integer $id
         *
         * @return SeoCategory the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if (( $model = SeoCategory::find()
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
